<?php

namespace App\Enums;

enum ComplaintStatus: string
{
    case Pending = 'pending';
    case AwaitingApprovers = 'awaiting_approvers';
    case ReadyToPublish = 'ready_to_publish';
    case Published = 'published';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Awaiting Admin Review',
            self::AwaitingApprovers => 'With Approvers',
            self::ReadyToPublish => 'Ready to Publish',
            self::Published => 'Published',
            self::Rejected => 'Rejected',
        };
    }
}
