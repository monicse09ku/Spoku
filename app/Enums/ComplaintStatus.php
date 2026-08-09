<?php

namespace App\Enums;

enum ComplaintStatus: string
{
    case Pending = 'pending';
    case Rejected = 'rejected';
    case Published = 'published';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending Approval',
            self::Rejected => 'Rejected',
            self::Published => 'Published',
        };
    }
}
