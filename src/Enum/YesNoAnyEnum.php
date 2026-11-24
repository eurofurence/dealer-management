<?php

namespace App\Enum;

/**
 * A simple enum for a boolean choice: yes/no/any.
 */
enum YesNoAnyEnum: string
{
    case Yes = 'yes';
    case No = 'no';
    case Any = 'any';
}
