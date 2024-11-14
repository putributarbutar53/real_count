<?php

namespace App\Controllers\suara24;

use App\Controllers\BaseController;
use App\Models\HasilProvModel;

class DataProv extends BaseController
{
    var $hasil;
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

        $columnIndex = $request->getVar('order')[0]['column'];
        $columnName = $request->getVar('columns')[$columnIndex]['data'];

        $columnSortOrder = $request->getVar('order')[0]['dir'];
        $searchValue = $request->getVar('search')['value'];

        $db = db_connect();

        // Hitung total record tanpa filter
        $totalRecords = $db->table('hasil_prov')->countAll();

        // Hitung total record dengan filter pencarian
        $totalRecordsWithFilter = $db->table('hasil_prov')
            ->join('paslon', 'paslon.id = hasil_prov.id_paslon')
            ->join('cpadmin', 'cpadmin.id = hasil_prov.id_user')
            ->join('kecamatan', 'kecamatan.id = hasil_prov.id_kec')
            ->join('desa', 'desa.id = hasil_prov.id_desa')
            ->where('hasil_prov.id !=', '0')
            ->groupStart()

            ->like('paslon.nama_paslon', $searchValue)  // Search by paslon name
            ->like('cpadmin.username', $searchValue)
            ->orLike('kecamatan.nama_kec', $searchValue)  // Search by kecamatan name
            ->orLike('desa.nama_desa', $searchValue)  // Search by desa name
            ->orLike('hasil_prov.tps', $searchValue)
            ->orLike('hasil_prov.tidak_sah', $searchValue)
            ->groupEnd()
            ->countAllResults();

        // Query data dengan join ke tabel lain
        $orderBy = ($columnName == '') ? 'hasil.id DESC' : $columnName . ' ' . $columnSortOrder;
        $data = $db->table('hasil_prov')
            ->select('hasil_prov.*, paslon.nama_paslon, kecamatan.nama_kec, desa.nama_desa, cpadmin.username')
            ->join('paslon', 'paslon.id = hasil_prov.id_paslon')
            ->join('cpadmin', 'cpadmin.id = hasil_prov.id_user')
            ->join('kecamatan', 'kecamatan.id = hasil_prov.id_kec')
            ->join('desa', 'desa.id = hasil_prov.id_desa')
            ->where('hasil_prov.id !=', '0')
            ->groupStart()
            ->like('paslon.nama_paslon', $searchValue)  // Search by paslon name
            ->like('cpadmin.username', $searchValue)
            ->orLike('kecamatan.nama_kec', $searchValue)  // Search by kecamatan name
            ->orLike('desa.nama_desa', $searchValue)  // Search by desa name
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
}
