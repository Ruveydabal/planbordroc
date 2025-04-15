<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;


class PingController extends Controller
{
    public function index() 
    {
        return view('ping');
    }

   
}