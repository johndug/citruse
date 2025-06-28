<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case MANAGER = 'manager';
    case SALES = 'sales';
    case LOGISTICS = 'logistics';
}
