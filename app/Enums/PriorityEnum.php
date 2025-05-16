<?php

namespace App\Enums;

enum PriorityEnum: string
{
    case LOW = 'low';
    case NORMAL = 'normal';
    case HIGH = 'high';

    public function label(): string
    {
        return match ($this) {
            self::LOW => 'Low',
            self::NORMAL => 'Normal',
            self::HIGH => 'High',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::LOW => 'bg-green-500',
            self::NORMAL => 'bg-yellow-500',
            self::HIGH => 'bg-red-500',
        };
    }
    public function badge(): string
    {
        return match ($this) {
            self::LOW => 'badge badge-secondary',
            self::NORMAL => 'badge badge-warning',
            self::HIGH => 'badge badge-danger',
        };
    }
}
