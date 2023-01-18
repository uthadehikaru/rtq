<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class Upload extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'lat'=>'required',
            'long'=>'required',
            'photo'=>'required',
            'batch_id'=>'required',
            'badal'=>'required',
        ]);
        $batch = Batch::find($data['batch_id']);
        $file = 'uploads/'.Carbon::now()->format('YmdHis').'.jpg';
        Storage::disk('public')->put($file, file_get_contents($data['photo']));
        $image = Image::make(Storage::disk('public')->get($file));
        $y = 10;
        $texts = $request->only(['lat','long']);
        $texts['Tanggal, jam'] = Carbon::now()->format('d M Y, H:i');
        $texts['Nama'] = Auth::user()->name;
        $texts['Halaqoh'] = $batch->name;
        $texts['Pengganti'] = $data['badal']?'Ya':'Tidak';
        $texts['posisi'] = $this->calculateDistance($texts).' meter';
        foreach($texts as $key=>$text){
            $image->text($key.' : '.$text, 10, $y, function($font) {  
                $font->file(public_path('assets/fonts/Roboto-Regular.ttf'));
                $font->size(14);
                $font->align('left');
                $font->valign('top');
            });
            $y += 20;
        }
        $image->save(storage_path('app/public/'.$file));
        $result['path'] = asset('storage/'.$file);
        return response()->json($result);
    }
}
