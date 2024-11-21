<?php

namespace App\Services;

use App\Models\Setting;

class SettingService
{
    public function get($name)
    {
        return Setting::where('name', $name)->first();
    }

    public function value($name, $default = null)
    {
        $value = $default;
        $setting = $this->get($name);
        if ($setting && $setting->payload) {
            $value = $setting->payload;
        }

        return $value;
    }

    public function set($name, $value)
    {
        $setting = $this->get($name);
        if ($setting) {
            $setting->update(['payload' => json_encode($value)]);
        }

        return $value;
    }
}
