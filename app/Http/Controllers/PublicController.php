<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;

class PublicController extends BaseController
{
    public function index(){
        return view('index');
    }
  
}