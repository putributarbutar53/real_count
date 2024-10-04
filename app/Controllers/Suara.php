<?php

namespace App\Controllers;

use App\Models\PaslonModel;
use App\Models\HasilModel;
use App\Models\KecamatanModel;
use App\Models\DesaModel;
use CodeIgniter\API\ResponseTrait;

class Suara extends BaseController
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
        $data['kecamatan'] = $this->kec->findAll();
        $data['paslon'] = $this->paslon->orderBy('id', 'ASC')->findAll();

        return view('web/input', $data);
    }

    public function getDesaByKecamatan($id_kec)
    {
        $desa = $this->desa->where('id_kec', $id_kec)->findAll();
        return $this->respond($desa);
    }
    public function save()
    {
        $data = [
            'id_kec' => $this->request->getPost('id_kec'),
            'id_desa' => $this->request->getPost('id_desa'),
            'id_paslon' => $this->request->getPost('id_paslon'),
            'tps' => $this->request->getPost('tps'),
            'suara_sah' => $this->request->getPost('suara_sah'),
            'tidak_sah' => $this->request->getPost('tidak_sah'),
            'jlh_suara' => $this->request->getPost('jlh_suara')
        ];

        $kodeKonfirmasi = $this->request->getPost('kode_konfirmasi');

        if ($kodeKonfirmasi !== '12345') {
            return redirect()->back()->with('error', 'Kode konfirmasi salah. Silakan coba lagi.');
        }

        $this->model->insert($data);

        return redirect()->to('suara')->with('success', 'Data berhasil disimpan.');
    }
}
