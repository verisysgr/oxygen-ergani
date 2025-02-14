<?php

namespace OxygenSuite\OxygenErgani\Enums;

enum UserType: string
{
    case EXTERNAL = '01';
    case ERGANI   = '02';
    case EFKA     = '03';
}