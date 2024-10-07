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
        $data['paslon'] = $this->paslon->orderBy('id', 'ASC')->findAll();
        $data['kecamatan'] = $this->kec->findAll();

        $hasil_suara = $this->model->select('hasil.id_kec, paslon.nama_paslon, hasil.suara_sah, hasil.tidak_sah')
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

        return view('web/hasil', $data);
    }

    public function getSuara()
    {
        $hasil_suara = $this->model->select('hasil.id_kec, paslon.nama_paslon, hasil.suara_sah, hasil.tidak_sah')
            ->join('paslon', 'paslon.id = hasil.id_paslon')
            ->findAll();

        $suara_per_kecamatan = [];

        foreach ($hasil_suara as $hasil) {
            $kec_id = $hasil['id_kec'];
            $paslon_nama = $hasil['nama_paslon'];
            $suara_sah = $hasil['suara_sah'];

            if (!isset($suara_per_kecamatan[$kec_id])) {
                $suara_per_kecamatan[$kec_id] = [
                    'nama_kec' => $this->kec->find($kec_id)['nama_kec'],
                    'suara' => []
                ];
            }

            if (!isset($suara_per_kecamatan[$kec_id]['suara'][$paslon_nama])) {
                $suara_per_kecamatan[$kec_id]['suara'][$paslon_nama] = [
                    'suara_sah' => 0,
                    'total_suara' => 0
                ];
            }

            $suara_per_kecamatan[$kec_id]['suara'][$paslon_nama]['suara_sah'] += $suara_sah;
            $suara_per_kecamatan[$kec_id]['suara'][$paslon_nama]['total_suara'] += ($suara_sah + $hasil['tidak_sah']);
        }

        return $this->response->setJSON($suara_per_kecamatan);
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
