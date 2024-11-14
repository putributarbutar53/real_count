<?php

namespace App\Models;

use CodeIgniter\Model;

class HasilModel extends Model
{
    protected $table      = 'hasil';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_paslon', 'id_kec', 'id_desa', 'tps', 'suara_sah', 'tidak_sah', 'jlh_suara', 'id_user'];

    public function getWithDetails()
    {
        return $this->select('hasil.*, paslon.nama_paslon, kecamatan.nama_kec, desa.nama_desa, hasil.tps')
            ->join('paslon', 'hasil.id_paslon = paslon.id')
            ->join('kecamatan', 'hasil.id_kec = kecamatan.id')
            ->join('desa', 'hasil.id_desa = desa.id')
            ->findAll();
    }
    public function getDataKec()
    {
        $data = $this->db->table('hasil')
            ->select('paslon.nama_paslon, kecamatan.nama_kec, SUM(hasil.suara_sah) as total_suara')
            ->join('paslon', 'paslon.id = hasil.id_paslon') // Join dengan tabel paslon
            ->join('kecamatan', 'kecamatan.id = hasil.id_kec') // Join dengan tabel kecamatan
            ->groupBy('hasil.id_paslon, hasil.id_kec') // Group by paslon dan kecamatan
            ->get()
            ->getResultArray();

        $totalSemuaSuara = 0;

        // Hitung total suara
        foreach ($data as $row) {
            $totalSemuaSuara += $row['total_suara'];
        }

        $labels = [];
        $totalSuara = [];
        $persentaseSuara = [];

        // Susun data untuk chart
        foreach ($data as $row) {
            // Menggunakan kombinasi nama kecamatan dan nama paslon sebagai label
            $labels[] = $row['nama_kec'] . ' - ' . $row['nama_paslon'];
            $totalSuara[] = $row['total_suara'];

            // Hitung persentase
            $persentaseSuara[] = ($row['total_suara'] / $totalSemuaSuara) * 100;
        }

        return [
            'labels' => $labels,
            'total_suara' => $totalSuara,
            'persentase_suara' => $persentaseSuara,
        ];
    }

    public function exportExcel()
    {
        return $this->select('hasil.*, paslon.nama_paslon, kecamatan.nama_kec, desa.nama_desa, hasil.tps')
            ->join('paslon', 'hasil.id_paslon = paslon.id')
            ->join('kecamatan', 'hasil.id_kec = kecamatan.id')
            ->join('desa', 'hasil.id_desa = desa.id')
            ->findAll();
    }
}
