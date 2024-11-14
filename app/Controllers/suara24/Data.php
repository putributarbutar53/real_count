<?php

namespace App\Controllers\suara24;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Models\KecamatanModel;
use App\Models\DesaModel;
use App\Models\HasilModel;
use App\Models\PaslonModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use CodeIgniter\API\ResponseTrait;

class Data extends BaseController
{
    var $model, $hasil;
    use ResponseTrait;
    function __construct()
    {
        $this->model = new AdminModel();
        $this->hasil = new HasilModel();
    }
    public function index()
    {
        return view('web/data/index');
    }
    function loaddata()
    {
        $request = service('request');

        $draw = $request->getVar('draw');
        $row = $request->getVar('start');
        $rowperpage = $request->getVar('length');

        $columnIndex = $request->getVar('order')[0]['column'];
        $columnName = $request->getVar('columns')[$columnIndex]['data'];

        $columnSortOrder = $request->getVar('order')[0]['dir'];
        $searchValue = $request->getVar('search')['value'];

        $db = db_connect();

        // Hitung total record tanpa filter
        $totalRecords = $db->table('hasil')->countAll();

        // Hitung total record dengan filter pencarian
        $totalRecordsWithFilter = $db->table('hasil')
            ->join('paslon', 'paslon.id = hasil.id_paslon')
            ->join('cpadmin', 'cpadmin.id = hasil.id_user')
            ->join('kecamatan', 'kecamatan.id = hasil.id_kec')
            ->join('desa', 'desa.id = hasil.id_desa')
            ->where('hasil.id !=', '0')
            ->groupStart()

            ->like('paslon.nama_paslon', $searchValue)  // Search by paslon name
            ->like('cpadmin.username', $searchValue)
            ->orLike('kecamatan.nama_kec', $searchValue)  // Search by kecamatan name
            ->orLike('desa.nama_desa', $searchValue)  // Search by desa name
            ->orLike('hasil.tps', $searchValue)
            ->orLike('hasil.tidak_sah', $searchValue)
            ->groupEnd()
            ->countAllResults();

        // Query data dengan join ke tabel lain
        $orderBy = ($columnName == '') ? 'hasil.id DESC' : $columnName . ' ' . $columnSortOrder;
        $data = $db->table('hasil')
            ->select('hasil.*, paslon.nama_paslon, kecamatan.nama_kec, desa.nama_desa, cpadmin.username')
            ->join('paslon', 'paslon.id = hasil.id_paslon')
            ->join('cpadmin', 'cpadmin.id = hasil.id_user')
            ->join('kecamatan', 'kecamatan.id = hasil.id_kec')
            ->join('desa', 'desa.id = hasil.id_desa')
            ->where('hasil.id !=', '0')
            ->groupStart()
            ->like('paslon.nama_paslon', $searchValue)  // Search by paslon name
            ->like('cpadmin.username', $searchValue)
            ->orLike('kecamatan.nama_kec', $searchValue)  // Search by kecamatan name
            ->orLike('desa.nama_desa', $searchValue)  // Search by desa name
            ->orLike('hasil.suara_sah', $searchValue)
            ->orLike('hasil.tidak_sah', $searchValue)
            ->groupEnd()
            ->orderBy($orderBy)
            ->limit($rowperpage, $row)
            ->get()
            ->getResult();

        // Response JSON untuk datatables
        $response = [
            'draw' => intval($draw),
            'iTotalRecords' => $totalRecords,
            'iTotalDisplayRecords' => $totalRecordsWithFilter,
            'aaData' => $data
        ];

        return $this->response->setJSON($response);
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
        $hasilModel = new HasilModel(); // Model untuk data hasil
        $paslonModel = new PaslonModel(); // Model untuk data paslon

        // Ambil semua data hasil
        $dataHasil = $hasilModel->select('hasil.*, kecamatan.nama_kec, desa.nama_desa, hasil.tps')
            ->join('kecamatan', 'hasil.id_kec = kecamatan.id')
            ->join('desa', 'hasil.id_desa = desa.id')
            ->findAll();

        // Ambil semua paslon untuk mendapatkan nama mereka
        $dataPaslon = $paslonModel->findAll();

        // Membuat spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Menambahkan header
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Nama Kecamatan');
        $sheet->setCellValue('C1', 'Nama Desa');
        $sheet->setCellValue('D1', 'TPS');
        $sheet->setCellValue('E1', 'Jumlah Suara');

        // Menambahkan header nama paslon di kolom F1, G1, H1
        $sheet->setCellValue('F1', $dataPaslon[0]['nama_paslon']); // Paslon 1
        $sheet->setCellValue('G1', $dataPaslon[1]['nama_paslon']); // Paslon 2
        $sheet->setCellValue('H1', $dataPaslon[2]['nama_paslon']); // Paslon 3

        // Mengisi data mulai dari baris kedua
        $row = 2;
        foreach ($dataHasil as $item) {
            $sheet->setCellValue('A' . $row, $item['id']);
            $sheet->setCellValue('B' . $row, $item['nama_kec']);
            $sheet->setCellValue('C' . $row, $item['nama_desa']);
            $sheet->setCellValue('D' . $row, $item['tps']);
            $sheet->setCellValue('E' . $row, $item['suara_sah']);

            // Mengisi jumlah suara paslon di kolom F, G, dan H
            $suaraPaslon = [
                1 => 0, // Paslon 1
                2 => 0, // Paslon 2
                3 => 0  // Paslon 3
            ];

            foreach ($dataHasil as $hasil) {
                if (isset($suaraPaslon[$hasil['id_paslon']])) {
                    $suaraPaslon[$hasil['id_paslon']] += $hasil['suara_sah'];
                }
            }

            $sheet->setCellValue('F' . $row, $suaraPaslon[1]);
            $sheet->setCellValue('G' . $row, $suaraPaslon[2]);
            $sheet->setCellValue('H' . $row, $suaraPaslon[3]);

            $row++;
        }

        // Membuat file Excel dan menyiapkan untuk diunduh
        $writer = new Xlsx($spreadsheet);
        $fileName = 'data paslon.xlsx';

        // Set header untuk download file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
