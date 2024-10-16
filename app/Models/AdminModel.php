<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table            = 'cpadmin';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'username',
        'password',
        'email',
        'role',
        'name',
        'token',
        'picture'
    ];

    function getData($param)
    {
        $data = $this->where('username', $param)->first();
        if (empty($data['id'])) $data = $this->where('email', $param)->first();
        return $data;
    }
}
