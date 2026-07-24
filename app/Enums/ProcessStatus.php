<?php

namespace App\Enums;

enum ProcessStatus: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case FAILED = 'failed';

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'zinc',
            self::PROCESSING => 'blue',
            self::COMPLETED => 'green',
            self::FAILED => 'red',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::PENDING => 'loading',
            self::PROCESSING => 'arrow-trending-up',
            self::COMPLETED => 'check',
            self::FAILED => 'x-mark',
        };
    }

    public function label(): string
    {
        return ucfirst($this->value);
    }
}
