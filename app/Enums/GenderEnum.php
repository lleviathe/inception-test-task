<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum GenderEnum: int
{
    use EnumTrait;

    case Male = 1;
    case Female = 2;
}
