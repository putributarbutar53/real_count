<?php

namespace App\Controllers\suara24;

use App\Controllers\BaseController;
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
        $id_kec = session()->get('admin_id_kec');
        if ($id_kec) {
            $data['kecamatan'] = $this->kec->where('id', $id_kec)->findAll();
        } else {
            $data['kecamatan'] = $this->kec->findAll();
        }
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
        $id_user = $this->request->getVar('id_user');
        $idKecamatan = $this->request->getPost('id_kec');
        $idDesa = $this->request->getPost('id_desa');
        $tpsNumbers = $this->request->getPost('tps_number');
        $idPaslonArray = $this->request->getPost('id_paslon');
        $suaraSahArray = $this->request->getPost('suara_sah');
        $tidakSahArray = $this->request->getPost('tidak_sah');

        $kecamatan = $this->kec->find($idKecamatan);
        $desa = $this->desa->find($idDesa);
        if (!$kecamatan || !$desa) {
            return redirect()->back()->with('error', 'Kecamatan atau Desa tidak ditemukan.');
        }

        $dataInserted = false;
        foreach ($tpsNumbers as $index => $tps) {
            $tidakSah = isset($tidakSahArray[$index]) ? $tidakSahArray[$index] : 0;
            foreach ($idPaslonArray as $paslonId) {
                $suaraSah = isset($suaraSahArray[$paslonId][$index]) ? $suaraSahArray[$paslonId][$index] : 0;

                // Cek jika data sudah ada
                $existingData = $this->model->where([
                    'id_kec' => $idKecamatan,
                    'id_desa' => $idDesa,
                    'tps' => $tps,
                    'id_paslon' => $paslonId,
                ])->first();

                if ($existingData) {
                    continue; // Lewati proses insert jika data sudah ada
                }

                // Jika data tidak ditemukan, insert
                $data = [
                    'id_kec' => $idKecamatan,
                    'id_desa' => $idDesa,
                    'tps' => $tps,
                    'id_paslon' => $paslonId,
                    'suara_sah' => $suaraSah,
                    'tidak_sah' => $tidakSah,
                    'jlh_suara' => $suaraSah + $tidakSah,
                    'id_user' => $id_user,
                ]; 

                $this->model->insert($data);
                $dataInserted = true;
            }
        }

        if ($dataInserted) {
            return redirect()->to('suara24/suara')->with('success', 'Data berhasil disimpan.');
        }
        return redirect()->to('suara24/suara')->with('info', 'Tidak ada data yang baru dimasukkan.');
    }

    public function checkDuplicate()
    {
        $kecamatan = $this->request->getPost('kecamatan');
        $desa = $this->request->getPost('desa');
        $tpsNumber = $this->request->getPost('tpsNumber');
        $exists = $this->model->where('id_kec', $kecamatan)
            ->where('id_desa', $desa)
            ->where('tps', $tpsNumber)
            ->first();
        if ($exists) {
            return $this->response->setJSON(['exists' => true]);
        } else {
            return $this->response->setJSON(['exists' => false]);
        }
    }
}
