<?php

namespace App\Controllers\suara24;

use App\Controllers\BaseController;
use App\Models\HasilProvModel;
use App\Models\KecamatanModel;
use App\Models\DesaModel;
use App\Models\PaslonModel;
use App\Models\HasilModel;
use CodeIgniter\API\ResponseTrait;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DataProv extends BaseController
{
    var $hasil;
    use ResponseTrait;
    function __construct()
    {
        $this->hasil = new HasilProvModel();
    }
    public function index()
    {
        return view('web/data/prove');
    }

    public function loaddata()
    {
        $request = service('request');

        $draw = $request->getVar('draw');
        $row = $request->getVar('start');
        $rowperpage = $request->getVar('length');

        $columnIndex = $request->getVar('order')[0]['column'] ?? 0;
        $columnName = $request->getVar('columns')[$columnIndex]['data'] ?? '';
        $columnSortOrder = $request->getVar('order')[0]['dir'] ?? 'desc';
        $searchValue = $request->getVar('search')['value'] ?? '';

        $db = db_connect();

        // Hitung total record tanpa filter
        $totalRecords = $db->table('hasil_prov')->countAll();

        // Hitung total record dengan filter pencarian
        $totalRecordsWithFilter = $db->table('hasil_prov')
            ->join('cpadmin', 'cpadmin.id = hasil_prov.id_user')
            ->join('kecamatan', 'kecamatan.id = hasil_prov.id_kec')
            ->join('desa', 'desa.id = hasil_prov.id_desa')
            ->where('hasil_prov.id !=', '0')
            ->groupStart()
            ->like('cpadmin.username', $searchValue)
            ->orLike('kecamatan.nama_kec', $searchValue)
            ->orLike('desa.nama_desa', $searchValue)
            ->orLike('hasil_prov.tps', $searchValue)
            ->orLike('hasil_prov.tidak_sah', $searchValue)
            ->groupEnd()
            ->countAllResults();

        // Tentukan sorting default
        $orderBy = empty($columnName) ? 'hasil_prov.id DESC' : $columnName . ' ' . $columnSortOrder;

        // Query data dengan join ke tabel lain
        $data = $db->table('hasil_prov')
            ->select("
            hasil_prov.*, 
            kecamatan.nama_kec, 
            desa.nama_desa, 
            cpadmin.username,
            CASE 
                WHEN hasil_prov.id_paslon = 1 THEN 'Bobby - Surya'
                WHEN hasil_prov.id_paslon = 2 THEN 'Edy - Hasan'
                ELSE 'Unknown Paslon'
            END AS nama_paslon
        ")
            ->join('cpadmin', 'cpadmin.id = hasil_prov.id_user')
            ->join('kecamatan', 'kecamatan.id = hasil_prov.id_kec')
            ->join('desa', 'desa.id = hasil_prov.id_desa')
            ->where('hasil_prov.id !=', '0')
            ->groupStart()
            ->like('cpadmin.username', $searchValue)
            ->orLike('kecamatan.nama_kec', $searchValue)
            ->orLike('desa.nama_desa', $searchValue)
            ->orLike('hasil_prov.suara_sah', $searchValue)
            ->orLike('hasil_prov.tidak_sah', $searchValue)
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

        echo view('web/data/formprov', $data);
    }

    function submitdata()
    {
        $action = $this->request->getVar('action');
        switch ($action) {
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

    public function exportExcel()
    {
        $kecamatanModel = new KecamatanModel();
        $desaModel = new DesaModel();
        $paslonModel = new PaslonModel();
        $hasilProv = new HasilProvModel();

        // Ambil data hasil yang di-*group* berdasarkan id_kecamatan, id_desa, dan tps
        $dataHasil = $hasilProv->select('id_kec, id_desa, tps')
            ->groupBy('id_kec, id_desa, tps')
            ->findAll();

        // Membuat spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Menambahkan header
        $sheet->setCellValue('A1', 'No'); // Nomor Urut
        $sheet->setCellValue('B1', 'Nama Kecamatan');
        $sheet->setCellValue('C1', 'Nama Desa');
        $sheet->setCellValue('D1', 'TPS');
        $sheet->setCellValue('E1', 'Bobby - Surya'); // Paslon 1
        $sheet->setCellValue('F1', 'Edy - Hasan'); // Paslon 2

        // Mengisi data mulai dari baris kedua
        $row = 2;
        $nomorUrut = 1; // Mulai nomor urut dari 1
        foreach ($dataHasil as $hasil) {
            // Ambil nama kecamatan dan desa
            $namaKecamatan = $kecamatanModel->find($hasil['id_kec'])['nama_kec'];
            $namaDesa = $desaModel->find($hasil['id_desa'])['nama_desa'];

            // Mengisi kolom A dengan nomor urut
            $sheet->setCellValue('A' . $row, $nomorUrut);
            $sheet->setCellValue('B' . $row, $namaKecamatan);
            $sheet->setCellValue('C' . $row, $namaDesa);
            $sheet->setCellValue('D' . $row, $hasil['tps']);

            // Ambil jumlah suara untuk setiap paslon
            $suaraPaslon = [
                1 => 0, // Paslon 1
                2 => 0  // Paslon 2
            ];

            // Cari data suara untuk kombinasi id_kecamatan, id_desa, dan tps
            $dataSuara = $hasilProv->where('id_kec', $hasil['id_kec'])
                ->where('id_desa', $hasil['id_desa'])
                ->where('tps', $hasil['tps'])
                ->findAll();

            // Hitung jumlah suara per paslon
            foreach ($dataSuara as $suara) {
                if (isset($suaraPaslon[$suara['id_paslon']])) {
                    $suaraPaslon[$suara['id_paslon']] = $suara['suara_sah'];
                }
            }

            // Isi jumlah suara paslon di kolom E dan F
            $sheet->setCellValue('E' . $row, $suaraPaslon[1]);
            $sheet->setCellValue('F' . $row, $suaraPaslon[2]);

            $row++;
            $nomorUrut++; // Tambahkan nomor urut
        }

        // Membuat file Excel dan menyiapkan untuk diunduh
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Perolehan Suara.xlsx';

        // Set header untuk download file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
