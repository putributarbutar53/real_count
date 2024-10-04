<?php

namespace App\Controllers;

use App\Models\PaslonModel;
use CodeIgniter\API\ResponseTrait;

class Suara extends BaseController
{
    var $model, $validation;
    use ResponseTrait;
    function __construct()
    {
        $this->model = new PaslonModel();
        $this->validation = \Config\Services::validation();
    }
    public function index(): string
    {
        $data['paslon'] = $this->model->orderBy('id', 'ASC')->findAll();
        return view('web/index.php', $data);
    }
}
