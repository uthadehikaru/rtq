<?php

namespace App\Http\Controllers;

use App\Models\Program;

class HomeController extends Controller
{
    public function index()
    {
        $data['programs'] = Program::latest()->whereNotNull('published_at')->take(3)->get();

        return view('home', $data);
    }
}
