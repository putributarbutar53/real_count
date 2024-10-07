<?php

namespace App\Controllers;

use App\Models\PaslonModel;
use App\Models\HasilModel;
use CodeIgniter\API\ResponseTrait;

class Chart extends BaseController
{
    var $model, $subdimensi, $validation, $hasil;
    use ResponseTrait;
    function __construct()
    {
        $this->model = new PaslonModel();
        $this->hasil = new HasilModel();
        $this->validation = \Config\Services::validation();
    }
    public function index(): string
    {

        return view('web/chart');
    }

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
}
