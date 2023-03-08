<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ResetPassword extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, $user_id)
    {
        $user = User::find($user_id);
        if(!$user)
            return back()->with('error','Pengguna tidak ditemukan');

        $newPassword = Str::random(6);
        if($user->hasRole('member'))
            $newPassword = $user->member->nik;
        else
            $newPassword = 'bismillah';
        $user->password = Hash::make($newPassword);
        $user->save();
        return back()->with('message','Password pengguna diubah menjadi '.$newPassword);
    }
}
