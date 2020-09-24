<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth'])->except(['login','register']);
    }

    public function register(){
        
    }

    public function login(){

    }

    public function renewToken(){

    }

    public function userData(){

    }
}
