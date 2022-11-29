<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginAsUser extends Controller
{
    public function __invoke(Request $request, User $user)
    {
        Auth::login($user);

        return redirect()->intended(route('dashboard'));
    }
}
