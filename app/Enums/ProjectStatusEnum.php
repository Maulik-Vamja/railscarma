<?php

namespace App\Enums;

enum ProjectStatusEnum: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::IN_PROGRESS => 'In Progress',
            self::COMPLETED => 'Completed',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'bg-yellow-500',
            self::IN_PROGRESS => 'bg-blue-500',
            self::COMPLETED => 'bg-green-500',
        };
    }
}
