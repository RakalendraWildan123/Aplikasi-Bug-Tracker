<?php

namespace App\Enums;

enum ActionItemStatus: string
{
    case Planned = 'planned';
    case ConvertedToTask = 'converted_to_task';
    case Completed = 'completed';

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
