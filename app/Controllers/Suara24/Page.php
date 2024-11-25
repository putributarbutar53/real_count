<?php

namespace App\Controllers\suara24;

use App\Controllers\BaseController;

class Page extends BaseController
{
    public function index()
    {
        return view('web/page_maintenance');
    }
}
