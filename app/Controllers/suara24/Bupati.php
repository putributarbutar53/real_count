<?php

namespace App\Controllers\suara24;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Models\KecamatanModel;
use App\Models\DesaModel;
use App\Models\HasilModel;
use App\Models\HasilProvModel;
use App\Models\PaslonModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use CodeIgniter\API\ResponseTrait;

class Bupati extends BaseController
{
    var $model, $hasil, $paslon, $kec, $desa;
    use ResponseTrait;
    function __construct()
    {
        $this->model = new AdminModel();
        $this->kec = new KecamatanModel();
        $this->desa = new DesaModel();
        $this->paslon = new PaslonModel();
        $this->hasil = new HasilModel();
    }
    public function index()
    {
        // Mengambil data dari tabel hasil dengan join ke tabel kecamatan, desa, dan paslon
        $data['hasil'] = $this->hasil->select('hasil.*, paslon.nama_paslon, kecamatan.nama_kec, desa.nama_desa, cpadmin.username')
            ->join('paslon', 'paslon.id = hasil.id_paslon')
            ->join('kecamatan', 'kecamatan.id = hasil.id_kec')
            ->join('desa', 'desa.id = hasil.id_desa')
            ->join('cpadmin', 'cpadmin.id = hasil.id_user')
            ->orderBy('kecamatan.nama_kec', 'ASC')
            ->orderBy('desa.nama_desa', 'ASC')
            ->orderBy('hasil.tps', 'ASC')
            ->orderBy("FIELD(paslon.nama_paslon, 'Poltak - Anugerah', 'Robinson - Tonny', 'Effendi - Murphy')") // Urutkan paslon
            ->findAll();

        return view('web/bupati/index', $data);
    }



    function edit($id)
    {
        $data['title'] = "Edit";
        $data['detail'] = $this->hasil->find($id);
        $data['action'] = "update";
        $data['tombol'] = "Update Data";

        echo view('web/data/form', $data);
    }
    function submitdata()
    {
        $action = $this->request->getVar('action');
        switch ($action) {
                // case "add":
                //     $requestData = $this->request->getPost();
                //     $check = $this->model->where('slug', $this->request->getPost('slug'))->first();
                //     if (!empty($check['slug'])) {
                //         $requestData['slug'] = $check['slug'] . "-" . rand();
                //     }
                //     $image = $this->request->getFile('picture');
                //     if ($image->isValid()) {
                //         $newName = $image->getRandomName();
                //         $image->move(ROOTPATH . 'public/' . getenv('dir.upload.articles'), $newName);
                //         $requestData['picture'] = $newName;
                //     }
                //     $requestData['created_at'] = date('Y-m-d H:i:s');
                //     $requestData['updated_at'] = date('Y-m-d H:i:s');
                //     $this->model->insert($requestData);
                //     return $this->respond([
                //         'status' => 'success',
                //         'message' => 'Data inserted successfully'
                //     ], 200);
            case "update":
                $requestData = array(
                    'suara_sah' => $this->request->getVar('suara_sah'),
                );
                $detail = $this->hasil->find($this->request->getVar('id'));
                $requestData['updated_at'] = date('Y-m-d H:i:s');
                $this->hasil->update($detail['id'], $requestData);
                return $this->respond([
                    'status' => 'success',
                    'message' => 'Data updated successfully'
                ], 200);
        }
    }
    public function deletedata($id)
    {
        $deleted = $this->hasil->delete($id);
        if ($deleted) {
            return $this->respond([
                'status' => 'success',
                'message' => 'Data deleted successfully'
            ], 200);
        } else {
            return $this->respond([
                'message' => 'Ops! Id tidak valid'
            ], 400);
        }
    }
    public function exportExcel()
    {
        $hasilModel = new HasilModel();
        $kecamatanModel = new KecamatanModel();
        $desaModel = new DesaModel();
        $paslonModel = new PaslonModel();

        // Ambil data hasil yang di-*group* berdasarkan id_kecamatan, id_desa, dan tps
        $dataHasil = $hasilModel->select('id_kec, id_desa, tps')
            ->groupBy('id_kec, id_desa, tps')
            ->findAll();

        // Ambil semua data paslon
        $dataPaslon = $paslonModel->findAll();

        // Membuat spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Menambahkan header
        $sheet->setCellValue('A1', 'Nama Kecamatan');
        $sheet->setCellValue('B1', 'Nama Desa');
        $sheet->setCellValue('C1', 'TPS');
        $sheet->setCellValue('D1', $dataPaslon[0]['nama_paslon']); // Paslon 1
        $sheet->setCellValue('E1', $dataPaslon[1]['nama_paslon']); // Paslon 2
        $sheet->setCellValue('F1', $dataPaslon[2]['nama_paslon']); // Paslon 3

        // Mengisi data mulai dari baris kedua
        $row = 2;
        foreach ($dataHasil as $hasil) {
            // Ambil nama kecamatan dan desa
            $namaKecamatan = $kecamatanModel->find($hasil['id_kec'])['nama_kec'];
            $namaDesa = $desaModel->find($hasil['id_desa'])['nama_desa'];

            $sheet->setCellValue('A' . $row, $namaKecamatan);
            $sheet->setCellValue('B' . $row, $namaDesa);
            $sheet->setCellValue('C' . $row, $hasil['tps']);

            // Ambil jumlah suara untuk setiap paslon
            $suaraPaslon = [
                1 => 0, // Paslon 1
                2 => 0, // Paslon 2
                3 => 0  // Paslon 3
            ];

            // Cari data suara untuk kombinasi id_kecamatan, id_desa, dan tps
            $dataSuara = $hasilModel->where('id_kec', $hasil['id_kec'])
                ->where('id_desa', $hasil['id_desa'])
                ->where('tps', $hasil['tps'])
                ->findAll();

            // Hitung jumlah suara per paslon
            foreach ($dataSuara as $suara) {
                $suaraPaslon[$suara['id_paslon']] = $suara['suara_sah'];
            }

            // Isi jumlah suara paslon di kolom D, E, dan F
            $sheet->setCellValue('D' . $row, $suaraPaslon[1]);
            $sheet->setCellValue('E' . $row, $suaraPaslon[2]);
            $sheet->setCellValue('F' . $row, $suaraPaslon[3]);

            $row++;
        }

        // Membuat file Excel dan menyiapkan untuk diunduh
        $writer = new Xlsx($spreadsheet);
        $fileName = 'data_export.xlsx';

        // Set header untuk download file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function exportExcelprov()
    {
        $hasilModel = new HasilModel();
        $kecamatanModel = new KecamatanModel();
        $desaModel = new DesaModel();
        $paslonModel = new PaslonModel();
        $hasilProv = new HasilProvModel();
        // Ambil data hasil yang di-*group* berdasarkan id_kecamatan, id_desa, dan tps
        $dataHasil = $hasilProv->select('id_kec, id_desa, tps')
            ->groupBy('id_kec, id_desa, tps')
            ->findAll();

        // Ambil semua data paslon
        $dataPaslon = $paslonModel->findAll();

        // Membuat spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Menambahkan header
        $sheet->setCellValue('A1', 'Nama Kecamatan');
        $sheet->setCellValue('B1', 'Nama Desa');
        $sheet->setCellValue('C1', 'TPS');
        $sheet->setCellValue('D1', $dataPaslon[0]['nama_paslon']); // Paslon 1
        $sheet->setCellValue('E1', $dataPaslon[1]['nama_paslon']); // Paslon 2
        $sheet->setCellValue('F1', $dataPaslon[2]['nama_paslon']); // Paslon 3

        // Mengisi data mulai dari baris kedua
        $row = 2;
        foreach ($dataHasil as $hasil) {
            // Ambil nama kecamatan dan desa
            $namaKecamatan = $kecamatanModel->find($hasil['id_kec'])['nama_kec'];
            $namaDesa = $desaModel->find($hasil['id_desa'])['nama_desa'];

            $sheet->setCellValue('A' . $row, $namaKecamatan);
            $sheet->setCellValue('B' . $row, $namaDesa);
            $sheet->setCellValue('C' . $row, $hasil['tps']);

            // Ambil jumlah suara untuk setiap paslon
            $suaraPaslon = [
                1 => 0, // Paslon 1
                2 => 0, // Paslon 2
                3 => 0  // Paslon 3
            ];

            // Cari data suara untuk kombinasi id_kecamatan, id_desa, dan tps
            $dataSuara = $hasilModel->where('id_kec', $hasil['id_kec'])
                ->where('id_desa', $hasil['id_desa'])
                ->where('tps', $hasil['tps'])
                ->findAll();

            // Hitung jumlah suara per paslon
            foreach ($dataSuara as $suara) {
                $suaraPaslon[$suara['id_paslon']] = $suara['suara_sah'];
            }

            // Isi jumlah suara paslon di kolom D, E, dan F
            $sheet->setCellValue('D' . $row, $suaraPaslon[1]);
            $sheet->setCellValue('E' . $row, $suaraPaslon[2]);
            $sheet->setCellValue('F' . $row, $suaraPaslon[3]);

            $row++;
        }

        // Membuat file Excel dan menyiapkan untuk diunduh
        $writer = new Xlsx($spreadsheet);
        $fileName = 'data_export.xlsx';

        // Set header untuk download file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
