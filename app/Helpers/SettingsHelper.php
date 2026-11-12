<?php

use App\Models\Setting;

if (!function_exists('setting')) {
    /**
     * Récupérer une valeur de setting
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting($key, $default = null)
    {
        return Setting::get($key, $default);
    }
}

if (!function_exists('settings_group')) {
    /**
     * Récupérer tous les settings d'un groupe
     * 
     * @param string $group
     * @return \Illuminate\Support\Collection
     */
    function settings_group($group)
    {
        return Setting::getGroup($group);
    }
}