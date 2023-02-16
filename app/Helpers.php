<?php

use App\Models\Setting;

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
