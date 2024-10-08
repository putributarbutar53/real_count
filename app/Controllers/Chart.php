<?php

namespace App\Controllers;

use App\Models\PaslonModel;
use App\Models\HasilModel;
use App\Models\KecamatanModel;
use CodeIgniter\API\ResponseTrait;

class Chart extends BaseController
{
    var $model, $kec, $validation, $hasil;
    use ResponseTrait;
    function __construct()
    {
        $this->model = new PaslonModel();
        $this->hasil = new HasilModel();
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
    // Fungsi untuk mendapatkan data chart
    public function getchart()
    {
        // Join tabel hasil dengan paslon untuk mendapatkan nama_paslon
        $data = $this->hasil
            ->select('paslon.nama_paslon, SUM(hasil.suara_sah) as total_suara')
            ->join('paslon', 'paslon.id = hasil.id_paslon') // Join dengan tabel paslon
            ->groupBy('hasil.id_paslon')
            ->findAll();

        $totalSemuaSuara = 0;
        foreach ($data as $row) {
            $totalSemuaSuara += $row['total_suara'];
        }

        $labels = [];
        $totalSuara = [];
        $persentaseSuara = [];

        foreach ($data as $row) {
            // Menggunakan nama_paslon sebagai label
            $labels[] = $row['nama_paslon'];
            $totalSuara[] = $row['total_suara'];

            $persentaseSuara[] = ($row['total_suara'] / $totalSemuaSuara) * 100;
        }

        return $this->respond([
            'labels' => $labels, // Nama paslon akan tampil di chart
            'total_suara' => $totalSuara, // Untuk bar chart
            'persentase_suara' => $persentaseSuara // Untuk pie chart
        ]);
    }

    public function getGrafikByKecamatan()
    {
        $selectedKec = $this->request->getPost('kecamatan');
        $data = $this->hasil
            ->select('paslon.nama_paslon, SUM(hasil.suara_sah) as total_suara')
            ->join('paslon', 'paslon.id = hasil.id_paslon') // Join dengan tabel paslon
            ->groupBy('hasil.id_paslon')
            ->findAll();
        $dataGrafik = [];
        $labels = [];
        foreach ($data as $row) {
            $labels[] = $row['nama_paslon'];
        }

        if ($selectedKec) {
            foreach ($selectedKec as $id_kec) {
                $dataSuara = $this->hasil->select('id_paslon, SUM(suara_sah) as total_suara')
                    ->where('id_kec', $id_kec)
                    ->groupBy('id_paslon')
                    ->findAll();

                $kecamatan = $this->kec->find($id_kec);
                $dataGrafik[] = [
                    'kecamatan' => $kecamatan['nama_kec'],
                    'data' => $dataSuara
                ];
            }
        }

        return $this->respond([
            'dataGrafik' => $dataGrafik,
            'labels' => $labels,
        ]);
    }
}
