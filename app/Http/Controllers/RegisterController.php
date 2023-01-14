<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function index(Request $request, $type)
    {
        $data['type'] = $type;
        return view('register', $data);
    }

    public function submit(Request $request, $type)
    {
        $data = $request->validate([
            'nik'=>'required|size:16',
            'full_name'=>'required|max:255',
            'short_name'=>'required|max:255',
            'gender'=>'required|in:male,female',
            'birth_place'=>'required|max:255',
            'birth_date'=>'required|date',
            'address'=>'required|max:255',
            'phone'=>'required|max:255',
            'email'=>'required|email',
            'father_name'=>'max:255',
            'mother_name'=>'max:255',
            'school_level'=>'max:255',
            'school_name'=>'max:255',
            'class'=>'max:255',
            'school_start_time'=>'',
            'school_end_time'=>'',
            'start_school'=>'',
            'schedule_option'=>'',
            'reference'=>'required|max:255',
            'reference_schedule'=>'max:255',
            'term_condition'=>'required',
        ]);

        $exists = Registration::where('nik',$data['nik'])->first();
        if($exists)
            return response()->json(['success'=>false,'message'=>'NIK sudah terdaftar'], 500);

        $last = Registration::whereYear('created_at',Carbon::now()->format('Y'))
        ->whereMonth('created_at',Carbon::now()->format('m'))
        ->count();
        $data['registration_no'] = date("Ym").Str::padLeft(++$last, 3, '0');
        $data['type'] = $type;
        $registration = Registration::create($data);
        return response()->json(['success'=>true]);
    }
}
