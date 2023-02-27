<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

if (! function_exists('thumbnail')) {
    function thumbnail($path, $width, $height, $force=false)
    {
        $path = str_replace('/','\\',$path);
        
        if(!Storage::disk('public')->exists($path))
            return asset('assets/images/default.jpg');
        
        $filename = basename($path);
        $thumbnail = 'thumbnail_'.$filename;
        $thumbnail_path = storage_path('app/public/thumbnails/');
        if(Storage::missing($thumbnail_path))
            Storage::disk('public')->makeDirectory('thumbnails');

        $thumbnail_path .= $thumbnail;
        if(Storage::missing($thumbnail_path) || $force){
            $image = Image::make(Storage::disk('public')->get($path))->orientate()->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $image->save($thumbnail_path);
        }
        return asset('storage/thumbnails/'.$thumbnail);
    }
}

if (! function_exists('setting')) {
    function setting($name, $group = null)
    {
        $params['name'] = $name;
        if ($group) {
            $params['group'] = $group;
        }
        $setting = Setting::where($params)->first();
        $value = null;
        if ($setting) {
            $value = json_decode($setting->payload, true);
        }
        if (! $value) {
            $value = $setting->payload;
        }

        return $value;
    }
}
