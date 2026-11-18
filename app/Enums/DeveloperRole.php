<?php

namespace App\Enums;

enum DeveloperRole: string
{
    case FullstackLead = 'Fullstack Lead';
    case Backend = 'Backend';
    case Frontend = 'Frontend';
    case Mobile = 'Mobile';

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
