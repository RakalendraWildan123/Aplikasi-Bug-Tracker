<?php

namespace App\Enums;

enum TaskType: string
{
    case Feature = 'feature';
    case Enhancement = 'enhancement';
    case Refactor = 'refactor';
    case Maintenance = 'maintenance';

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
