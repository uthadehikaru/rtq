<?php

namespace App\Http\Controllers;

use App\Repositories\MemberRepository;
use App\Rules\Nik;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function index(Request $request, $type)
    {
        $data['type'] = $type;

        return view('register', $data);
    }

    public function submit(Request $request, MemberRepository $memberRepository, $type)
    {
        $data = $request->validate([
            'nik' => ['required', new Nik],
            'full_name' => 'required|max:255',
            'short_name' => 'required|max:255',
            'gender' => 'required|in:male,female',
            'birth_place' => 'required|max:255',
            'birth_date' => 'required|date',
            'address' => 'required|max:255',
            'phone' => 'required|max:255',
            'email' => 'required|email',
            'father_name' => 'max:255',
            'mother_name' => 'max:255',
            'is_yatim' => 'nullable',
            'school_level' => 'max:255',
            'school_name' => 'max:255',
            'class' => 'max:255',
            'school_start_time' => '',
            'school_end_time' => '',
            'start_school' => '',
            'schedule_option' => '',
            'reference' => 'required|max:255',
            'reference_schedule' => 'max:255',
            'term_condition' => 'required',
        ]);

        $memberRepository->register($type, $data);

        return response()->json(['success' => true]);
    }
}
