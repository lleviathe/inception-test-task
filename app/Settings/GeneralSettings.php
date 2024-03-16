<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    /* Wheel Spin cooldown in hours */
    public int $wheel_spin_cooldown;

    public static function group(): string
    {
        return 'general';
    }
}
