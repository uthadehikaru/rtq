<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['homepage'] = Setting::where('group', 'homepage')->get()->keyBy('name');
        $data['general'] = Setting::where('group', 'general')->get()->keyBy('name');
        $data['course'] = Setting::where('group', 'course')->get()->keyBy('name');
        $data['types'] = Course::TYPES;

        return view('forms.setting', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $params = [
            'banner' => 'nullable',
            'tagline' => 'nullable',
            'about' => 'nullable',
            'instagram' => 'nullable',
            'whatsapp' => 'nullable',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
        ];

        $types = Course::TYPES;
        foreach($types as $type){
            $params['durasi_'.str($type)->snake()] = 'min:0';
        }

        $data = $request->validate($params);

        foreach ($data as $name => $value) {
            Setting::where('name', $name)->update(['payload' => json_encode($value)]);
        }

        return back()->with('message', 'Pengaturan disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
