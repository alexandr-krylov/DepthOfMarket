<?php

namespace app\enums;

enum Status: int
{
    case Active = 1;
    case Filled = 2;
    case PartialFilled = 3;
    case Canseled = 4;
    case Refused = 5;
    case Redempted = 6;
}
