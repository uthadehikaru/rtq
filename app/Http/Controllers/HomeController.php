<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Auth;
use DB;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check())
            return redirect('dashboard');
        else
            return redirect('login');
    }

    public function dashboard()
    {
        $data['title'] = __('controllerhome.title.dashboard');
        
        return view('dashboard', $data);
    }
}
