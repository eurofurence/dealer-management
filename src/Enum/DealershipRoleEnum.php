<?php

namespace App\Enum;

/**
 * Roles for users within a dealership.
 */
enum DealershipRoleEnum: string
{
    case MainDealer = 'main';
    case ShareDealer = 'share';
    case Assistant = 'assistant';
}
