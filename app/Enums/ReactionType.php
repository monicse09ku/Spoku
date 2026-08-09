<?php

namespace App\Enums;

enum ReactionType: string
{
    case Spitting = 'spitting';
    case Cursing = 'cursing';
    case ShoeThrowing = 'shoe_throwing';
    case Burning = 'burning';
    case Disgust = 'disgust';
    case Outrage = 'outrage';

    public function label(): string
    {
        return match ($this) {
            self::Spitting => 'Spitting',
            self::Cursing => 'Cursing',
            self::ShoeThrowing => 'Shoe Throwing',
            self::Burning => 'Burning',
            self::Disgust => 'Disgust',
            self::Outrage => 'Outrage',
        };
    }

    public function emoji(): string
    {
        return match ($this) {
            self::Spitting => '🤮',
            self::Cursing => '🤬',
            self::ShoeThrowing => '👟',
            self::Burning => '🔥',
            self::Disgust => '🤢',
            self::Outrage => '😡',
        };
    }
}
