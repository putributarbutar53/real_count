<?php

namespace App\Controllers;

use App\Models\PaslonModel;
use App\Models\HasilModel;
use App\Models\KecamatanModel;
use App\Models\DptModel;
use App\Models\HasilProvModel;
use CodeIgniter\API\ResponseTrait;

class Chart extends BaseController
{
    var $model, $kec, $validation, $hasil, $hasilprov, $dpt;
    use ResponseTrait;
    function __construct()
    {
        $this->model = new PaslonModel();
        $this->hasil = new HasilModel();
        $this->dpt = new DptModel();
        $this->hasilprov = new HasilProvModel();
        $this->kec = new KecamatanModel();
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
    public function loadTps()
    {
        // Ambil data dari tabel tps
        $tpsData = $this->dpt->findAll(); // Mengambil semua data TPS
        $hasilData = $this->hasil->findAll(); // Mengambil data hasil yang sudah diinput

        // Hitung jumlah TPS yang sudah diinput
        $inputTps = [];
        foreach ($hasilData as $h) {
            $inputTps[] = [
                'id_kec' => $h['id_kec'],
                'id_desa' => $h['id_desa'],
                'tps' => $h['tps'],
            ];
        }

        // Hitung jumlah TPS di tabel dpt
        $totalTps = count($tpsData);

        // Hitung jumlah TPS yang sudah diinput
        $uniqueTps = [];
        foreach ($inputTps as $t) {
            $uniqueTps[$t['id_kec']][$t['id_desa']][$t['tps']] = true;
        }

        $tpsInputed = 0;
        foreach ($uniqueTps as $kec => $desa) {
            foreach ($desa as $d => $tps) {
                $tpsInputed++;
            }
        }

        // Hitung sisa TPS yang belum diinput
        $sisaTps = $totalTps - $tpsInputed;

        // Hitung persentase TPS yang sudah diinput
        $persen = ($tpsInputed / $totalTps) * 100;

        // Kirim data dalam format JSON
        return $this->response->setJSON([
            'tps_inputed' => $tpsInputed,
            'sisa_tps' => $sisaTps,
            'persen' => round($persen, 2)
        ]);
    }
    public function getSuara()
    {
        $hasilModel = new HasilModel();

        // Menghitung total suara sah
        $totalSuaraSah = $hasilModel->selectSum('suara_sah')->first();
        $tidakSahTotal = $this->hasil
            ->select('id_kec, id_desa, tps, MAX(tidak_sah) as total_tidak_sah')
            ->groupBy('id_kec, id_desa, tps')
            ->findAll();

        // Hitung total tidak sah dari hasil query di atas
        $totalTidakSah = array_sum(array_column($tidakSahTotal, 'total_tidak_sah'));

        $totalDpt = 150643; // Ganti dengan jumlah DPT yang sesuai

        $suaraPaslon = [
            'total_suara_sah' => $totalSuaraSah['suara_sah'],
            'total_suara_tidak' => $totalTidakSah,
            'suara_poltak' => $hasilModel->where('id_paslon', 1)->selectSum('suara_sah')->first()['suara_sah'] ?? 0,
            'suara_robinson' => $hasilModel->where('id_paslon', 2)->selectSum('suara_sah')->first()['suara_sah'] ?? 0,
            'suara_effendi' => $hasilModel->where('id_paslon', 3)->selectSum('suara_sah')->first()['suara_sah'] ?? 0,
            'total_dpt' => $totalDpt // Menambahkan total DPT
        ];
        return $this->response->setJSON($suaraPaslon);
    }
    public function getSuaraProv()
    {
        $totalSuaraSah = $this->hasilprov->selectSum('suara_sah')->first();

        $tidakSahTotal = $this->hasilprov
            ->select('id_kec, id_desa, tps, MAX(tidak_sah) as total_tidak_sah')
            ->groupBy('id_kec, id_desa, tps')
            ->findAll();
        $totalTidakSah = array_sum(array_column($tidakSahTotal, 'total_tidak_sah'));

        $totalDpt = 150643;

        $suaraPaslon = [
            'total_suara_sah' => $totalSuaraSah['suara_sah'],
            'total_suara_tidak' => $totalTidakSah,
            'suara_bobby' => $this->hasilprov->where('id_paslon', 1)->selectSum('suara_sah')->first()['suara_sah'] ?? 0,
            'suara_edy' => $this->hasilprov->where('id_paslon', 2)->selectSum('suara_sah')->first()['suara_sah'] ?? 0,
            'total_dpt' => $totalDpt
        ];

        return $this->response->setJSON($suaraPaslon);
    }
    public function getchart()
    {
        $data = $this->hasil
            ->select('paslon.nama_paslon, hasil.id_paslon, SUM(hasil.suara_sah) as total_suara')
            ->join('paslon', 'paslon.id = hasil.id_paslon')
            ->groupBy('hasil.id_paslon')
            ->findAll();

        $tidakSahTotal = $this->hasil
            ->select('id_kec, id_desa, tps, MAX(tidak_sah) as total_tidak_sah')
            ->groupBy('id_kec, id_desa, tps')
            ->findAll();

        $totalTidakSah = array_sum(array_column($tidakSahTotal, 'total_tidak_sah'));

        $totalSuaraSah = $this->hasil
            ->select('SUM(hasil.suara_sah) as total_suara_sah')
            ->first();

        $totalSuaraSah = $totalSuaraSah ? $totalSuaraSah['total_suara_sah'] : 0;

        $labels = [];
        $totalSuara = [];
        $persentaseSuara = [];

        foreach ($data as $row) {
            $paslonName = $row['nama_paslon'];

            $totalSuaraPaslon = $totalSuaraSah + $totalTidakSah;

            $persentase = $totalSuaraPaslon > 0 ? ($row['total_suara'] / $totalSuaraPaslon) * 100 : 0;

            $labels[] = $paslonName;
            $totalSuara[] = $row['total_suara'];
            $persentaseSuara[] = $persentase;
        }

        $totalSuaraAll = $totalSuaraSah + $totalTidakSah;
        $persentaseTidakSah = $totalSuaraAll > 0 ? ($totalTidakSah / $totalSuaraAll) * 100 : 0;

        return $this->respond([
            'labels' => $labels,
            'total_suara' => $totalSuara,
            'persentase_suara' => $persentaseSuara,
            'tidak_sah' => $totalTidakSah,
            'persentase_tidak_sah' => $persentaseTidakSah
        ]);
    }


    public function getchartprov()
    {
        $data = $this->hasilprov
            ->select('hasil_prov.id_paslon, SUM(hasil_prov.suara_sah) as total_suara')
            ->groupBy('hasil_prov.id_paslon')
            ->findAll();

        $tidakSahTotal = $this->hasilprov
            ->select('id_kec, id_desa, tps, MAX(tidak_sah) as total_tidak_sah')
            ->groupBy('id_kec, id_desa, tps')
            ->findAll();

        $totalTidakSah = $tidakSahTotal ? array_sum(array_column($tidakSahTotal, 'total_tidak_sah')) : 0;

        $totalSuaraSah = $this->hasilprov
            ->select('SUM(hasil_prov.suara_sah) as total_suara_sah')
            ->first();

        $totalSuaraSah = $totalSuaraSah ? $totalSuaraSah['total_suara_sah'] : 0;

        $labels = [];
        $totalSuara = [];
        $persentaseSuara = [];

        foreach ($data as $row) {
            $paslonName = ($row['id_paslon'] == 1) ? 'Bobby - Surya' : (($row['id_paslon'] == 2) ? 'Edy - Hasan' : 'Unknown');

            $totalSuaraPaslon = $totalSuaraSah + $totalTidakSah;

            $persentase = $totalSuaraPaslon > 0 ? ($row['total_suara'] / $totalSuaraPaslon) * 100 : 0;

            $labels[] = $paslonName;
            $totalSuara[] = $row['total_suara'];
            $persentaseSuara[] = $persentase;
        }

        $totalSuaraAll = $totalSuaraSah + $totalTidakSah;
        $persentaseTidakSah = $totalSuaraAll > 0 ? ($totalTidakSah / $totalSuaraAll) * 100 : 0;

        return $this->respond([
            'labels' => $labels,
            'total_suara' => $totalSuara,
            'persentase_suara' => $persentaseSuara,
            'tidak_sah' => $totalTidakSah,
            'persentase_tidak_sah' => $persentaseTidakSah
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
            ->orderBy('paslon.id', 'ASC')
            ->findAll();

        $dataGrafik = [];
        $labels = [];
        foreach ($data as $row) {
            $labels[] = $row['nama_paslon'];
        }

        if ($selectedKec) {
            foreach ($selectedKec as $id_kec) {
                // Menghitung total suara sah per paslon untuk kecamatan yang dipilih
                $dataSuara = $this->hasil
                    ->select('id_paslon, SUM(suara_sah) as total_suara')
                    ->where('id_kec', $id_kec)
                    ->groupBy('id_paslon')
                    ->orderBy('id_paslon', 'ASC')
                    ->findAll();
                $totalSuaraKecamatan = array_sum(array_column($dataSuara, 'total_suara'));

                // Menghitung total suara tidak sah berdasarkan kombinasi unik id_kec, id_desa, dan tps
                $tidakSahData = $this->hasil
                    ->select('id_kec, id_desa, tps, SUM(tidak_sah) as total_tidak_sah')
                    ->where('id_kec', $id_kec)
                    ->groupBy('id_kec', 'id_desa', 'tps')
                    ->findAll();
                $totalTidakSah = array_sum(array_column($tidakSahData, 'total_tidak_sah'));

                // Mencari kombinasi unik `id_kec`, `id_desa`, dan `tps` di tabel `hasil`
                $uniqueTpsCombinations = $this->hasil
                    ->select('id_kec, id_desa, tps')
                    ->where('id_kec', $id_kec)
                    ->groupBy(['id_kec', 'id_desa', 'tps']) // Pastikan tps dihitung sebagai kombinasi unik
                    ->findAll();

                // Menghitung total DPT berdasarkan kombinasi unik dari tabel `dpt`
                $totalDpt = 0;
                foreach ($uniqueTpsCombinations as $combination) {
                    // Ambil data DPT berdasarkan `id_kec`, `id_desa`, dan `tps`
                    $dptDataList = $this->dpt
                        ->select('jlh_dpt')
                        ->where('id_kec', $combination['id_kec'])
                        ->where('id_desa', $combination['id_desa'])
                        ->where('nomor_tps', $combination['tps'])
                        ->findAll(); // Menggunakan findAll untuk memastikan setiap data dihitung

                    // Menambahkan setiap `jlh_dpt` yang ditemukan untuk setiap kombinasi unik `id_kec`, `id_desa`, dan `tps`
                    foreach ($dptDataList as $dpt) {
                        $totalDpt += $dpt['jlh_dpt'];
                    }
                }

                // Mendapatkan nama kecamatan
                $kecamatan = $this->kec->find($id_kec);
                $dataPaslon = [];

                foreach ($dataSuara as $suara) {
                    // Menggunakan total DPT sebagai pembagi untuk menghitung persentase suara sah per paslon
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
