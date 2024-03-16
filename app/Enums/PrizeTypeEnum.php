<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum PrizeTypeEnum: int
{
    use EnumTrait;

    case Custom = 1;
    case LotteryTicket = 2;
}
