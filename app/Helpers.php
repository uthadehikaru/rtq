<?php

use App\Models\Setting;

if(!function_exists('setting')){
    function setting($name, $group=null)
    {
        $params['name'] = $name;
        if($group)
            $params['group'] = $group;
        $setting = Setting::where($params)->first();
        if($setting)
            return json_decode($setting->payload, true);

        return null;
    }
}