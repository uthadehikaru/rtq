<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Models\Present;
use Illuminate\Http\Request;

class AddMemberToSchedule extends Controller
{
    public function __invoke(Request $request, $schedule_id)
    {
        $data = $request->validate([
            'user_id'=>'required',
            'status'=>'required',
            'description'=>'',
        ]);

        $present = Present::where([
            'schedule_id'=>$schedule_id,
            'user_id'=>$data['user_id'],
        ])->first();

        if($present)
            return back()->with('error','Peserta sudah ada didaftar hadir');

        $data['schedule_id'] = $schedule_id;
        $data['type']='member';
        $present = Present::create($data);
        return back()->with('message','Peserta berhasil ditambahkan');
    }
}
