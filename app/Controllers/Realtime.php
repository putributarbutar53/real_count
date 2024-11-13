<?php

namespace App\Controllers;

use App\Models\PaslonModel;
use App\Models\HasilModel;
use App\Models\KecamatanModel;
use App\Models\DptModel;
use CodeIgniter\API\ResponseTrait;
use Pusher\Pusher;
use GuzzleHttp\Client;

class Realtime extends BaseController
{
    protected $pusher, $dpt, $hasil;
    use ResponseTrait;
    public function __construct()
    {
        $this->hasil = new HasilModel();
        $this->dpt = new DptModel();
        $options = array(
            'cluster' => 'ap1',
            'useTLS' => true
        );
        $httpClient = new Client([
            'verify' => false,
        ]);

        $this->pusher = new Pusher(
            '9ac0d2af743317b62be2',
            '63c22ca53e56ea2bccba',
            '1889285',
            $options,
            $httpClient
        );
    }
    public function index()
    {
        // Memuat model DptModel untuk mengambil data DPT
        $dptModel = $this->dpt;

        // Query untuk mendapatkan total suara sah per paslon
        $data = $this->hasil
            ->select('paslon.nama_paslon, hasil.id_paslon, SUM(hasil.suara_sah) as total_suara')
            ->join('paslon', 'paslon.id = hasil.id_paslon')
            ->groupBy('hasil.id_paslon')
            ->findAll();

        // Query untuk mendapatkan total suara tidak sah, dihitung sekali per kombinasi id_kec, id_desa, dan tps
        $tidakSahTotal = $this->hasil
            ->select('id_kec, id_desa, tps, MAX(tidak_sah) as total_tidak_sah')
            ->groupBy('id_kec, id_desa, tps')
            ->findAll();

        // Hitung total tidak sah dari hasil query di atas
        $totalTidakSah = array_sum(array_column($tidakSahTotal, 'total_tidak_sah'));

        // Ambil kombinasi unik dari `id_kec`, `id_desa`, dan `tps` di tabel `hasil`
        $uniqueTpsCombinations = $this->hasil
            ->select('id_kec, id_desa, tps')
            ->groupBy('id_kec, id_desa, tps')
            ->findAll();

        // Menghitung total DPT berdasarkan kombinasi unik
        $totalDpt = 0;
        foreach ($uniqueTpsCombinations as $combination) {
            // Ambil data dari tabel DPT berdasarkan kombinasi id_kec, id_desa, dan nomor_tps
            $dptData = $dptModel->where([
                'id_kec' => $combination['id_kec'],
                'id_desa' => $combination['id_desa'],
                'nomor_tps' => $combination['tps']
            ])->first();

            // Jika data ditemukan, tambahkan jlh_dpt ke totalDpt
            if ($dptData) {
                $totalDpt += $dptData['jlh_dpt'];
            }
        }

        $labels = [];
        $totalSuara = [];
        $persentaseSuara = [];

        foreach ($data as $row) {
            // Menggunakan total DPT untuk menghitung persentase suara sah per paslon
            $persentase = $totalDpt > 0 ? ($row['total_suara'] / $totalDpt) * 100 : 0;

            // Simpan data ke array untuk respons JSON
            $labels[] = $row['nama_paslon'];
            $totalSuara[] = $row['total_suara'];
            $persentaseSuara[] = $persentase;
        }
        // Hitung persentase untuk suara tidak sah berdasarkan totalDpt
        $persentaseTidakSah = $totalDpt > 0 ? ($totalTidakSah / $totalDpt) * 100 : 0;

        // Data yang akan dikirim ke Pusher
        $chartData = [
            'labels' => $labels,
            'total_suara' => $totalSuara,
            'persentase_suara' => $persentaseSuara,
            'tidak_sah' => $totalTidakSah, // Total suara tidak sah
            'persentase_tidak_sah' => $persentaseTidakSah, // Persentase suara tidak sah
            'total_dpt' => $totalDpt // Total DPT
        ];


        // Kirim data ke channel Pusher
        $this->pusher->trigger('my-channel', 'my-event', $chartData);

        // Kembalikan respons JSON
        return $this->respond($chartData);
    }
}
