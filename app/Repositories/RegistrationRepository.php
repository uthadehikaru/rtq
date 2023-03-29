<?php

namespace App\Repositories;

use App\Events\MemberActivated;
use App\Models\Member;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegistrationRepository
{
    public function activate($id, $data)
    {
        DB::beginTransaction();
        $registration = Registration::findOrFail($id);

        $member = Member::where('nik', $registration->nik)->first();
        if ($member) {
            $registration->update(['user_id' => $member->user_id]);

            return;
        }

        // create user
        $email = $registration->email;
        $existingUser = User::where('email', $email)->exists();
        if ($existingUser) {
            $email = Str::random().'@rtqmaisuro.id';
        }
        $user = User::create([
            'email' => $email,
            'password' => Hash::make($registration->nik),
            'name' => $registration->full_name,
        ]);

        $user->assignRole('member');

        // create member
        $member = Member::create([
            'user_id' => $user->id,
            'nik' => $registration->nik,
            'registration_date' => $data['registration_date'],
            'full_name' => $registration->full_name,
            'short_name' => $registration->short_name,
            'birth_date' => $registration->birth_date,
            'gender' => $registration->gender,
            'address' => $registration->address,
            'phone' => $registration->phone,
            'email' => $registration->email,
            'school' => $registration->school_name,
            'class' => $registration->class,
        ]);

        // register batch
        $member->batches()->attach($data['batch_id']);

        MemberActivated::dispatch($member);

        Artisan::call('member:generateno');

        $registration->update(['user_id' => $user->id]);

        DB::commit();
    }
}
