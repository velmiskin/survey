<?php

namespace App\Component\User\Domain\Enum;

enum Role: string
{
    case ROLE_PATIENT = 'ROLE_PATIENT';
    case ROLE_ADMIN = 'ROLE_ADMIN';
    case ROLE_EMPLOYEE = 'ROLE_EMPLOYEE';
}
