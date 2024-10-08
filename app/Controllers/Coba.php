<?php

namespace App\Controllers;

use App\Models\PaslonModel;
use App\Models\HasilModel;
use App\Models\KecamatanModel;
use CodeIgniter\API\ResponseTrait;

class Coba extends BaseController
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
        $data['kecamatan'] = $this->kec->findAll();
        return view('web/coba', $data);
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
