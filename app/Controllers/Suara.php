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
        $idKecamatan = $this->request->getPost('id_kec');
        $idDesa = $this->request->getPost('id_desa');
        $tpsNumbers = $this->request->getPost('tps_number');
        $idPaslonArray = $this->request->getPost('id_paslon');
        $suaraSahArray = $this->request->getPost('suara_sah');
        $tidakSahArray = $this->request->getPost('tidak_sah');

        $kodeKonfirmasi = $this->request->getPost('kode_konfirmasi');

        if ($kodeKonfirmasi !== '12345') {
            return redirect()->back()->with('error', 'Kode konfirmasi salah. Silakan coba lagi.');
        }

        // Ambil nama kecamatan dan desa
        $kecamatan = $this->kec->find($idKecamatan);
        $desa = $this->desa->find($idDesa);

        // Cek apakah data kecamatan dan desa ditemukan
        if (!$kecamatan || !$desa) {
            return redirect()->back()->with('error', 'Kecamatan atau Desa tidak ditemukan.');
        }

        $dataInserted = false; // Menandakan apakah ada data yang dimasukkan

        // Looping untuk setiap TPS
        foreach ($tpsNumbers as $index => $tps) {
            // Cek apakah suara sah dan tidak sah ada untuk indeks ini
            $tidakSah = isset($tidakSahArray[$index]) ? $tidakSahArray[$index] : 0;

            // Looping untuk setiap paslon
            foreach ($idPaslonArray as $paslonId) {
                // Ambil suara sah untuk paslon ini
                $suaraSah = isset($suaraSahArray[$paslonId][$index]) ? $suaraSahArray[$paslonId][$index] : 0;

                // Masukkan data ke database
                $data = [
                    'id_kec' => $idKecamatan,
                    'id_desa' => $idDesa,
                    'tps' => $tps,
                    'id_paslon' => $paslonId,
                    'suara_sah' => $suaraSah,
                    'tidak_sah' => $tidakSah,
                    'jlh_suara' => $suaraSah + $tidakSah // Jumlah suara = suara sah + suara tidak sah
                ];

                $this->model->insert($data); // Menyimpan data ke database
                $dataInserted = true; // Tandai bahwa ada data yang dimasukkan
            }
        }

        // Jika ada data yang dimasukkan, tampilkan pesan sukses
        if ($dataInserted) {
            return redirect()->to('suara')->with('success', 'Data berhasil disimpan.');
        }

        // Jika tidak ada data yang dimasukkan
        return redirect()->to('suara')->with('info', 'Tidak ada data yang baru dimasukkan.');
    }
    public function checkDuplicate()
    {
        $kecamatan = $this->request->getPost('kecamatan');
        $desa = $this->request->getPost('desa');
        $tpsNumber = $this->request->getPost('tpsNumber');

        // Lakukan query ke database untuk memeriksa data
        $exists = $this->model->where('id_kec', $kecamatan)
            ->where('id_desa', $desa)
            ->where('tps', $tpsNumber)
            ->first();

        // Kirim respons ke JavaScript
        if ($exists) {
            return $this->response->setJSON(['exists' => true]);
        } else {
            return $this->response->setJSON(['exists' => false]);
        }
    }
}
