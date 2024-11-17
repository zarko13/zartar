<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

class BaseController extends Controller
{
    public $osType;

    public function __construct(){
        $this->middleware(function ($request, $next) {
            $this->osType =PHP_OS;
            return $next($request);           
        });
    }
}