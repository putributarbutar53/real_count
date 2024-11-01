<?php

namespace App\Controllers;

use App\Models\PaslonModel;
use App\Models\HasilModel;
use App\Models\KecamatanModel;
use App\Models\DptModel;
use CodeIgniter\API\ResponseTrait;

class Chart extends BaseController
{
    var $model, $kec, $validation, $hasil, $dpt;
    use ResponseTrait;
    function __construct()
    {
        $this->model = new PaslonModel();
        $this->hasil = new HasilModel();
        $this->dpt = new DptModel();
        $this->kec = new KecamatanModel();
        $this->validation = \Config\Services::validation();
    }
    public function index(): string
    {
        $data['paslon'] = $this->model->orderBy('id', 'ASC')->findAll();
        $data['kecamatan'] = $this->kec->findAll();

        $hasil_suara = $this->hasil->select('hasil.id_kec, paslon.nama_paslon, hasil.suara_sah, hasil.tidak_sah')
            ->join('paslon', 'paslon.id = hasil.id_paslon')
            ->findAll();

        $data['suara_per_kecamatan'] = [];

        foreach ($hasil_suara as $hasil) {
            $kec_id = $hasil['id_kec'];
            $paslon_nama = $hasil['nama_paslon'];
            $suara_sah = $hasil['suara_sah'];

            if (!isset($data['suara_per_kecamatan'][$kec_id])) {
                $data['suara_per_kecamatan'][$kec_id] = [
                    'nama_kec' => $this->kec->find($kec_id)['nama_kec'],
                    'suara' => []
                ];
            }

            if (!isset($data['suara_per_kecamatan'][$kec_id]['suara'][$paslon_nama])) {
                $data['suara_per_kecamatan'][$kec_id]['suara'][$paslon_nama] = [
                    'suara_sah' => 0,
                    'total_suara' => 0
                ];
            }

            $data['suara_per_kecamatan'][$kec_id]['suara'][$paslon_nama]['suara_sah'] += $suara_sah;
            $data['suara_per_kecamatan'][$kec_id]['suara'][$paslon_nama]['total_suara'] += ($suara_sah + $hasil['tidak_sah']);
        }
        $data['kecamatan'] = $this->kec->findAll();
        return view('web/chart', $data);
    }
    public function getSuara()
    {
        $hasilModel = new HasilModel();

        // Menghitung total suara sah
        $totalSuaraSah = $hasilModel->selectSum('suara_sah')->first();

        // Menghitung suara untuk setiap paslon
        $suaraPaslon = [
            'total_suara' => $totalSuaraSah['suara_sah'],
            'suara_poltak' => $hasilModel->where('id_paslon', 1)->selectSum('suara_sah')->first()['suara_sah'] ?? 0,
            'suara_robinson' => $hasilModel->where('id_paslon', 2)->selectSum('suara_sah')->first()['suara_sah'] ?? 0,
            'suara_effendi' => $hasilModel->where('id_paslon', 3)->selectSum('suara_sah')->first()['suara_sah'] ?? 0,
        ];

        return $this->response->setJSON($suaraPaslon);
    }
    public function getchart()
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

    // Mendapatkan total DPT berdasarkan kombinasi id_kec, id_desa, dan tps yang ada di tabel hasil
    $uniqueTpsCombinations = $this->hasil
        ->select('id_kec, id_desa, tps')
        ->groupBy('id_kec, id_desa, tps')
        ->findAll();

    $totalDpt = 0;
    foreach ($uniqueTpsCombinations as $combination) {
        $dptData = $dptModel->where([
            'id_kec' => $combination['id_kec'],
            'id_desa' => $combination['id_desa'],
            'nomor_tps' => $combination['tps']
        ])->first();

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

    return $this->respond([
        'labels' => $labels,
        'total_suara' => $totalSuara,
        'persentase_suara' => $persentaseSuara,
        'tidak_sah' => $totalTidakSah // Kirim total tidak sah ke respons
    ]);
}

public function getGrafikByKecamatan()
{
    $selectedKec = $this->request->getPost('kecamatan');

    // Mengambil label untuk setiap paslon
    $data = $this->hasil
        ->select('paslon.id, paslon.nama_paslon, SUM(hasil.suara_sah) as total_suara')
        ->join('paslon', 'paslon.id = hasil.id_paslon')
        ->groupBy('hasil.id_paslon')
        ->orderBy('paslon.id', 'ASC') // Pastikan diurutkan berdasarkan ID paslon
        ->findAll();

    $dataGrafik = [];
    $labels = [];
    foreach ($data as $row) {
        $labels[] = $row['nama_paslon'];
    }

    if ($selectedKec) {
        foreach ($selectedKec as $id_kec) {
            // Menghitung total suara sah per paslon untuk kecamatan yang dipilih
            $dataSuara = $this->hasil->select('id_paslon, SUM(suara_sah) as total_suara')
                ->where('id_kec', $id_kec)
                ->groupBy('id_paslon')
                ->orderBy('id_paslon', 'ASC') // Mengurutkan berdasarkan ID paslon
                ->findAll();
            $totalSuaraKecamatan = array_sum(array_column($dataSuara, 'total_suara'));

            // Menghitung total tidak sah dengan hanya sekali per kombinasi id_kec, id_desa, dan tps
            $tidakSahData = $this->hasil
                ->select('id_kec, id_desa, tps, MAX(tidak_sah) as total_tidak_sah')
                ->where('id_kec', $id_kec)
                ->groupBy('id_kec', 'id_desa', 'tps')
                ->findAll();
            $totalTidakSah = array_sum(array_column($tidakSahData, 'total_tidak_sah'));

            // Mendapatkan total DPT untuk kecamatan yang dipilih
            $uniqueTpsCombinations = $this->hasil
                ->select('id_kec, id_desa, tps')
                ->where('id_kec', $id_kec)
                ->groupBy('id_kec', 'id_desa', 'tps')
                ->findAll();

            $totalDpt = 0;
            foreach ($uniqueTpsCombinations as $combination) {
                $dptData = $this->dpt->where([
                    'id_kec' => $combination['id_kec'],
                    'id_desa' => $combination['id_desa'],
                    'nomor_tps' => $combination['tps']
                ])->first();

                if ($dptData) {
                    $totalDpt += $dptData['jlh_dpt'];
                }
            }

            // Mendapatkan nama kecamatan
            $kecamatan = $this->kec->find($id_kec);
            $dataPaslon = [];

            foreach ($dataSuara as $suara) {
                // Menggunakan total DPT sebagai pembagi untuk menghitung persentase
                $persentase = $totalDpt > 0 ? (($suara['total_suara'] / $totalDpt) * 100) : 0;
                $dataPaslon[] = [
                    'id_paslon' => $suara['id_paslon'],
                    'total_suara' => $suara['total_suara'],
                    'persentase' => number_format($persentase, 2)
                ];
            }

            // Memasukkan data kecamatan beserta total suara tidak sah dan total DPT
            $dataGrafik[] = [
                'kecamatan' => $kecamatan['nama_kec'],
                'data' => $dataPaslon,
                'total_suara_kecamatan' => $totalSuaraKecamatan,
                'total_tidak_sah' => $totalTidakSah,
                'total_dpt' => $totalDpt // Menyertakan total DPT dalam respons
            ];
        }
    }

    return $this->respond([
        'dataGrafik' => $dataGrafik,
        'labels' => $labels,
    ]);
}

}
