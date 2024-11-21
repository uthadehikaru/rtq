<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class CheckUserRole extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $users = User::doesntHave('roles')->select('id')->get()->pluck('id');
        $memberRole = Role::where('name', 'member')->first();
        $memberRole->users()->attach($users);

        return back()->with('message', 'Role Updated');
    }
}
