<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Repositories\ScheduleRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class CreateSchedule extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request,
    ScheduleRepository $scheduleRepository)
    {
        $data = $request->validate([
            'lat'=>'required',
            'long'=>'required',
            'photo'=>'required',
            'batch_id'=>'required',
            'badal'=>'required',
        ]);
        $batch = Batch::find($data['batch_id']);
        $file = 'jadwal/'.Auth::user()->name.' '.$batch->name.' '.Carbon::now()->format('d-M-Y H-i').'.jpg';
        Storage::disk('public')->put($file, file_get_contents($data['photo']));
        $image = Image::make(Storage::disk('public')->get($file));
        $image->rectangle(5, 5, 250, 150, function ($draw) {
            $draw->background('rgba(255, 255, 255, 0.5)');
            $draw->border(2, '#000');
        });
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

        if (Carbon::now()->lessThan($batch->start_time)) {
            return response(['error'=>'Absen halaqoh '.$batch->name.' hanya bisa dilakukan pada jam '.$batch->start_time->format('H:i')]);
        }

        try {
            $data = [
                'batch_id' => $request->get('batch_id'),
                'is_badal' => $request->get('badal'),
                'photo'=>$file,
            ];
            $schedule = $scheduleRepository->createByTeacher($data);

            if ($schedule) {
                $result['error'] = null;
                $result['schedule_id'] = $schedule->id;
                $result['path'] = asset('storage/'.$file);
                return response()->json($result);
            }

            return response()->json(['error'=>'Gagal membuat jadwal']);
        } catch(Exception $ex) {
            return response()->json(['error'=>$ex->getMessage()]);
        }
    }

    private function calculateDistance($data)
    {
        $lat = setting('latitude');
        $long = setting('longitude');
        //Converting to radians
        $longi1 = deg2rad($data['long']); 
        $longi2 = deg2rad($long); 
        $lati1 = deg2rad($data['lat']); 
        $lati2 = deg2rad($lat); 
            
        //Haversine Formula 
        $difflong = $longi2 - $longi1; 
        $difflat = $lati2 - $lati1; 
            
        $val = pow(sin($difflat/2),2)+cos($lati1)*cos($lati2)*pow(sin($difflong/2),2); 
              
        return round(1000 * 6378.8 * (2 * asin(sqrt($val)))); // in meters
    }
}
