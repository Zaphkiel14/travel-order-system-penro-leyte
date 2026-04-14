<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class TestController extends BaseController
{
    public function index()
    {

      return view('client/home', [
        'title' => 'Home',
        'description' => 'Welcome to the Travel Order System of PENRO Leyte'
      ]);
    }
}
