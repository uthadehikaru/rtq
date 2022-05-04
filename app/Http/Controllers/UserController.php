<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class UserController extends Controller
{
    public function index()
    {
        if (Auth::user()->cannot('list', Auth::user())) {
            return abort(403);
        }
        $data['title'] = __('Users');
        $data['users'] = User::orderBy('name')->get();
        $data['total'] = User::count();
        return view('datatables.user', $data);
    }
}
