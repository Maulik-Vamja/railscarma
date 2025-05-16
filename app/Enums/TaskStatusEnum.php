<?php

namespace App\Enums;

enum TaskStatusEnum: string
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

    public function badge(): string
    {
        return match ($this) {
            self::PENDING => 'badge badge-warning',
            self::IN_PROGRESS => 'badge badge-primary',
            self::COMPLETED => 'badge badge-success',
        };
    }
}
