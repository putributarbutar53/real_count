<?php

namespace App\Controllers;

use App\Models\PaslonModel;
use App\Models\HasilModel;
use App\Models\KecamatanModel;
use App\Models\DesaModel;
use CodeIgniter\API\ResponseTrait;

class Hasil extends BaseController
{
    var $model, $paslon, $tps, $kec, $desa, $validation;
    use ResponseTrait;
    function __construct()
    {
        $this->model = new HasilModel();
        $this->paslon = new PaslonModel();
        $this->kec = new KecamatanModel();
        $this->desa = new DesaModel();
        $this->validation = \Config\Services::validation();
    }
    public function index(): string
    {
        $data['hasil_suara'] = $this->model->select('paslon.nama_paslon, hasil.tps, hasil.suara_sah, hasil.tidak_sah, kecamatan.nama_kec, desa.nama_desa')
            ->join('kecamatan', 'kecamatan.id = hasil.id_kec')
            ->join('desa', 'desa.id = hasil.id_desa')
            ->join('paslon', 'paslon.id = hasil.id_paslon')
            ->findAll();

        $data['paslon'] = $this->paslon->orderBy('id', 'ASC')->findAll();
        $data['kecamatan'] = $this->kec->findAll();

        return view('web/hasil', $data);
    }
    public function getHasilSuara()
    {
        $hasil_suara = $this->model->select('paslon.nama_paslon, hasil.tps, hasil.suara_sah, hasil.tidak_sah, kecamatan.nama_kec, desa.nama_desa')
            ->join('kecamatan', 'kecamatan.id = hasil.id_kec')
            ->join('desa', 'desa.id = hasil.id_desa')
            ->join('paslon', 'paslon.id = hasil.id_paslon')
            ->findAll();

        return $this->response->setJSON($hasil_suara);
    }
}
