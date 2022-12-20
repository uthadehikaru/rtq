<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class Dropzone extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        if($request->has('name')) {
            Setting::where('name', $request->get('name'))->delete();
            return response()->json('success',200);
        }
        if($request->hasFile('file')) {
            //get filename with extension
            $filenamewithextension = $request->file('file')->getClientOriginalName();
       
            //get filename without extension
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
       
            //get file extension
            $extension = $request->file('file')->getClientOriginalExtension();
       
            //filename to store
            $filenametostore = $filename.'_'.time().'.'.$extension;
       
            //file File
            $request->file('file')->storeAs('public/uploads', $filenametostore);

            $url = asset('storage/uploads/'.$filenametostore);

            Setting::updateOrCreate([
                'name'=>'banner',
                'group'=>'homepage',
            ],[
                'payload' => $url,
            ]);

            return response()->json($url,200);
        }
        return response()->json('failed',400);
    }
}
