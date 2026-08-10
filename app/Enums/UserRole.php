<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Approver = 'approver';
    case Lawyer = 'lawyer';
    case Counselor = 'counselor';
    case LawEnforcer = 'law_enforcer';
    case Humanitarian = 'humanitarian';
    case User = 'user';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::Approver => 'Approver',
            self::Lawyer => 'Lawyer',
            self::Counselor => 'Counselor',
            self::LawEnforcer => 'Law Enforcer',
            self::Humanitarian => 'Humanitarian',
            self::User => 'User',
        };
    }

    public static function staffRoles(): array
    {
        return [
            self::Approver,
            self::Lawyer,
            self::Counselor,
            self::LawEnforcer,
            self::Humanitarian,
        ];
    }

    public function isStaff(): bool
    {
        return in_array($this, self::staffRoles(), true);
    }
}
