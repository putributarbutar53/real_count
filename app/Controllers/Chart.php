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
    // Fungsi untuk mendapatkan data chart
    public function getchart()
    {
        // Ambil data suara sah berdasarkan id_paslon
        $data = $this->hasil->select('id_paslon, SUM(suara_sah) as total_suara')
            ->groupBy('id_paslon')
            ->findAll();

        // Hitung total suara sah keseluruhan
        $totalSemuaSuara = 0;
        foreach ($data as $row) {
            $totalSemuaSuara += $row['total_suara'];
        }

        // Format data untuk chart
        $labels = [];
        $totalSuara = []; // Untuk Bar Chart
        $persentaseSuara = []; // Untuk Pie Chart

        foreach ($data as $row) {
            $labels[] = "Paslon " . $row['id_paslon'];
            $totalSuara[] = $row['total_suara'];

            // Hitung persentase suara sah untuk pie chart
            $persentaseSuara[] = ($row['total_suara'] / $totalSemuaSuara) * 100;
        }

        // Kirim respon sebagai JSON
        return $this->respond([
            'labels' => $labels,
            'total_suara' => $totalSuara, // Untuk bar chart
            'persentase_suara' => $persentaseSuara // Untuk pie chart
        ]);
    }
}