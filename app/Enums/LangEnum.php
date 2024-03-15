<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum LangEnum: string
{
    use EnumTrait;

    case En = 'en';
    case Ka = 'ka';
    case De = 'de';
}
