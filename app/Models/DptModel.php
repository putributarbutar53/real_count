<?php

namespace App\Models;

use CodeIgniter\Model;

class DptModel extends Model
{
    protected $table = 'tps';
    protected $primaryKey = 'id';
    
    protected $allowedFields = ['id_kec', 'id_desa', 'nomor_tps', 'jlh_dpt'];

    protected $useSoftDeletes = false;
    protected $useTimestamps = false;

    public function getAllTps()
    {
        return $this->findAll();
    }
    
    public function addTps($data)
    {
        return $this->insert($data);
    }

    public function updateTps($id, $data)
    {
        return $this->update($id, $data);
    }

    public function deleteTps($id)
    {
        return $this->delete($id);
    }
}
