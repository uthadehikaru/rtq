<?php

namespace App\Http\Controllers\Salary;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ConfigSalary extends Controller
{
    public function index()
    {
        $data['course_types'] = Course::TYPES;
        $data['courses'] = Course::orderBy('name')->get();
        $data['settings'] = Setting::group('salary')->pluck('payload', 'name');

        return view('forms.salary-config', $data);
    }

    public function save(Request $request)
    {
        $validations = [
            'oper_santri' => 'required',
            'transportasi' => 'required',
            'tunjangan' => 'required',
            'telat_tanpa_konfirmasi' => 'required',
            'maks_telat_dengan_konfirmasi' => 'required',
            'pengurangan_tunjangan_per_izin' => 'required',
            'maks_izin' => 'required',
        ];

        foreach (Course::TYPES as $type) {
            $validations[Str::snake($type)] = 'required';
        }

        $data = $request->validate($validations);
        foreach ($data as $name => $value) {
            $setting = [
                'name' => $name,
                'payload' => $value,
            ];

            Setting::updateOrCreate([
                'group' => 'salary',
                'name' => $name,
            ], [
                'payload' => $value,
            ]);
        }

        return back()->with('message', 'Konfigurasi Tersimpan');
    }
}
