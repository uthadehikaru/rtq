<?php

namespace App\Repositories;

use App\Events\BatchChanged;
use App\Events\BiodataUpdated;
use App\Interfaces\MemberRepositoryInterface;
use App\Models\Member;
use App\Models\Registration;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\RegisteredUser;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MemberRepository implements MemberRepositoryInterface
{
    public function all()
    {
        return Member::with('batches', 'batches.course')->orderBy('full_name')->get();
    }

    public function count()
    {
        return Member::count();
    }

    public function getLatest($limit = 10)
    {
        return Member::latest()->paginate($limit);
    }

    public function find($id)
    {
        return Member::findOrFail($id);
    }

    public function findByMemberNo($member_no)
    {
        return Member::where('member_no', $member_no)->first();
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $member = Member::findOrFail($id);
            $member->batches()->detach();
            $user = $member->user;
            $member->delete();
            $user->delete();

            return true;
        });
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            if ($data['nik']) {
                $nik = Member::where('nik', $data['nik'])->first();
                if ($nik) {
                    throw new Exception('NIK sudah terdaftar');
                }
            }

            $user = User::create([
                'email' => $data['email'],
                'name' => $data['full_name'],
                'password' => Hash::make($data['nik']),
            ]);

            $user->assignRole('member');

            $data['user_id'] = $user->id;

            if (isset($data['profile_picture'])) {
                $data['profile_picture'] = $data['profile_picture']->storePublicly('profiles', 'public');
            }
            $member = Member::create($data);

            if (isset($data['batch_id'])) {
                $member->batches()->sync($data['batch_id']);
            }

            return $member;
        });
    }

    public function updateMember($user_id, array $data)
    {
        if ($data['email']) {
            $exists = User::where('id', '<>', $user_id)->where('email', $data['email'])->first();
            if ($exists) {
                return 'Email yang diinput sudah digunakan. mohon gunakan email yang lain';
            }

            User::find($user_id)->update(['email' => $data['email']]);
        }

        Member::where('user_id', $user_id)->update($data);

        return null;
    }

    public function update($id, array $data)
    {
        if ($data['nik']) {
            $nik = Member::where('nik', $data['nik'])->where('id', '<>', $id)->first();
            if ($nik) {
                throw new Exception('NIK sudah terdaftar');
            }
        }

        return DB::transaction(function () use ($id, $data) {
            $member = Member::with('user')->find($id);

            if (! isset($data['batch_id']) && $member->batches->count()) {
                throw new Exception("Mohon tentukan halaqoh anggota. jika ingin mengeluarkan, silahkan melalui 'aksi' -> 'keluar halaqoh'.");
            }

            if (! isset($data['batch_id']) && ! $data['leave_at']) {
                throw new Exception('Tanggal keluar harus ditentukan apabila peserta inaktif');
            }

            if ($member->user) {
                $user = [
                    'email' => $data['email'],
                    'username' => $data['member_no'],
                    'name' => $data['full_name'],
                    'password' => Hash::make($data['nik']),
                ];
                $member->user()->update($user);
            } else {
                $user = User::create([
                    'email' => $data['member_no'] ?? $data['email'],
                    'name' => $data['full_name'],
                    'password' => Hash::make($data['nik']),
                ]);
                $data['user_id'] = $user->id;
            }

            if (isset($data['profile_picture'])) {
                $data['profile_picture'] = $data['profile_picture']->storePublicly('profiles', 'public');
                if ($member->profile_picture) {
                    Storage::disk('public')->delete($member->profile_picture);
                }

                $thumbnail = 'thumbnail/'.basename($member->profile_picture);
                Storage::disk('public')->delete('thumbnails/'.$thumbnail);
            } else {
                unset($data['profile_picture']);
            }

            if (isset($data['batch_id'])) {
                $data['leave_at'] = null;
            }

            $member->update($data);

            if (isset($data['profile_picture'])) {
                Artisan::call('member:card', ['--no' => $member->member_no]);
            }

            if (isset($data['batch_id'])) {
                $member->batches()->sync($data['batch_id']);
            } else {
                $member->batches()->detach();
            }

            return $member;
        });
    }

    public function countActiveMembers(): int
    {
        return Member::has('batches')->count();
    }

    public function updateBiodata($data)
    {
        $member = Member::findOrFail($data['member_id']);

        // extract birth date from nik
        // 3174082905880001
        // $date = Str::substr($data['nik'],6,2);
        // if($member->gender=='female')
        //     $date -= 40;
        // if($date<0)
        //     return "Tanggal lahir dan NIK tidak sesuai, mohon cek kembali";
        // $month = Str::substr($data['nik'],8,2);
        // $year = Str::substr($data['nik'],10,2);
        // $nikDate = Carbon::create($year,$month,$date);
        // if($nikDate!=$data['birth_date'])
        //     return "Tanggal lahir dan NIK tidak sesuai, mohon cek kembali";

        $biodata = Setting::where([
            'group' => 'biodata',
            'name' => $data['member_id'],
        ])
            ->first();

        if ($biodata) {
            $msg = 'Biodata sudah pernah diinput, ';
            if ($biodata->payload['verified']) {
                $msg .= 'dan sudah diverifikasi. terima kasih';
            } else {
                $msg .= 'sedang proses verifikasi. mohon tunggu';
            }
        }

        $biodata = Setting::where('group', 'biodata')->where('payload', 'like', '%'.$data['nik'].'%')->first();
        if ($biodata) {
            return 'NIK sudah digunakan, mohon pastikan NIK yang dimasukkan sesuai';
        }

        if ($data['profile_picture']) {
            $data['profile_picture'] = $data['profile_picture']->storePublicly('profiles', 'public');
        }

        $biodata = Setting::create([
            'group' => 'biodata',
            'name' => $data['member_id'],
            'payload' => $data,
        ]);

        BiodataUpdated::dispatch($member, $biodata);

        return null;
    }

    public function changeBatch($member_id, $batch_id)
    {
        DB::transaction(function () use ($member_id, $batch_id) {
            $member = $this->find($member_id);

            $old_batch = $member->batches->pluck('name')->join(',');

            $member->batches()->sync($batch_id);

            BatchChanged::dispatch($member, $old_batch);
        });
    }

    public function register($type, $data)
    {
        DB::beginTransaction();

        $exists = Registration::where('nik', $data['nik'])->first();
        if ($exists) {
            return response()->json(['success' => false, 'message' => 'NIK sudah terdaftar'], 500);
        }

        $last = Registration::whereYear('created_at', Carbon::now()->format('Y'))
            ->whereMonth('created_at', Carbon::now()->format('m'))
            ->count();
        $data['registration_no'] = date('Ym').Str::padLeft(++$last, 3, '0');
        $data['type'] = $type;
        $registration = Registration::create($data);

        $admins = User::role('administrator')->notify()->get();
        Notification::send($admins, new RegisteredUser($registration));

        DB::commit();
    }

    public function search($keyword)
    {
        $members = Member::select('id', 'full_name')
            ->whereRaw("lower(full_name) like '%".$keyword."%'")
            ->orderBy('full_name')->get();
        $data = [];
        foreach ($members as $member) {
            $data[] = [
                'id' => $member->id,
                'text' => $member->full_name,
            ];
        }

        return $data;
    }
}
