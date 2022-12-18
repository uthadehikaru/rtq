<?php

namespace App\Services;

use App\Models\Setting;

class SettingService
{
    public function get($name)
    {
        return Setting::where('name', $name)->first();
    }
    public function value($name)
    {
        $value = null;
        $setting = $this->get($name);
        if($setting)
            $value = $setting->payload;
        return $value;
    }
}