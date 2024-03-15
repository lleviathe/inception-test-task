<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum AuthenticableTypeEnum: string
{
    use EnumTrait;

    case Admin = 'admin';
    case User = 'user';
}
