<?php

namespace App\Repositories;

use App\Models\Member;
use App\Models\Registration;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegistrationRepository
{
    public function activate($id, $batch_id)
    {
        DB::beginTransaction();
        $registration = Registration::findOrFail($id);

        $member = Member::where('nik',$registration->nik)->first();
        if($member){
            $registration->update(['user_id'=>$member->user_id]);
            return;
        }

        // create user
        $user = User::create([
            'email'=>$registration->email,
            'password'=>Hash::make($registration->nik),
            'name'=>$registration->full_name,
        ]);

        // create member
        $member = Member::create([
            'user_id'=>$user->id,
            'registration_date'=>$registration->created_at,
            'full_name'=>$registration->full_name,
            'short_name'=>$registration->short_name,
            'birth_date'=>$registration->birth_date,
            'gender'=>$registration->gender,
            'address'=>$registration->address,
            'phone'=>$registration->phone,
            'email'=>$registration->email,
            'school'=>$registration->school_name,
            'class'=>$registration->class,
        ]);

        // register batch
        $member->batches()->attach($batch_id);
        
        Artisan::call('member:generateno');

        $registration->update(['user_id'=>$user->id]);

        DB::commit();

    }
}