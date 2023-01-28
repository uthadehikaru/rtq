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
            'user_id' => 'required',
            'status' => 'required',
            'description' => '',
        ]);

        foreach ($data['user_id'] as $user_id) {
            $present = Present::where([
                'schedule_id' => $schedule_id,
                'user_id' => $user_id,
            ])->first();

            if ($present) {
                continue;
            }

            $present = Present::create([
                'schedule_id' => $schedule_id,
                'type' => 'member',
                'is_transfer'=>true,
                'user_id' => $user_id,
            ]);
        }

        return back()->with('message', 'Peserta berhasil ditambahkan');
    }
}
