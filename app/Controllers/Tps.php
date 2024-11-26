<?php

namespace App\Controllers;

use App\Models\DptModel;
use App\Models\HasilModel;
use App\Models\AdminModel;
use CodeIgniter\API\ResponseTrait;

class Tps extends BaseController
{
    var $model, $dpt, $admin, $validation;
    use ResponseTrait;
    function __construct()
    {
        $this->model = new HasilModel();
        $this->dpt = new DptModel();
        $this->admin = new AdminModel();
        $this->validation = \Config\Services::validation();
    }
    public function index(): string
    {
        $dptBelumInput = $this->model->getTpsBelumInput();

        return view('web/tps', ['dptBelumInput' => $dptBelumInput]);
    }
}
