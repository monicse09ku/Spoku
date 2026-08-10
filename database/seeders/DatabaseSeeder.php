<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@spoku.test'],
            [
                'name' => 'SPOKU Admin',
                'phone' => '01000000000',
                'role' => UserRole::Admin,
                'is_active' => true,
                'password' => Hash::make('password'),
            ]
        );

        foreach (range(1, 10) as $i) {
            User::query()->updateOrCreate(
                ['email' => "approver{$i}@spoku.test"],
                [
                    'name' => "Approver {$i}",
                    'phone' => '010000000'.str_pad((string) $i, 2, '0', STR_PAD_LEFT),
                    'contact_info' => "Approver desk {$i}",
                    'role' => UserRole::Approver,
                    'is_active' => true,
                    'password' => Hash::make('password'),
                ]
            );
        }

        User::query()->updateOrCreate(
            ['email' => 'lawyer@spoku.test'],
            [
                'name' => 'Sample Lawyer',
                'phone' => '01111111111',
                'contact_info' => 'Legal aid desk',
                'role' => UserRole::Lawyer,
                'is_active' => true,
                'password' => Hash::make('password'),
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'counselor@spoku.test'],
            [
                'name' => 'Sample Counselor',
                'phone' => '01222222222',
                'contact_info' => 'Counseling support',
                'role' => UserRole::Counselor,
                'is_active' => true,
                'password' => Hash::make('password'),
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'enforcer@spoku.test'],
            [
                'name' => 'Sample Law Enforcer',
                'phone' => '01333333333',
                'contact_info' => 'Law enforcement liaison',
                'role' => UserRole::LawEnforcer,
                'is_active' => true,
                'password' => Hash::make('password'),
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'humanitarian@spoku.test'],
            [
                'name' => 'Sample Humanitarian',
                'phone' => '01555555555',
                'contact_info' => 'Humanitarian support desk',
                'role' => UserRole::Humanitarian,
                'is_active' => true,
                'password' => Hash::make('password'),
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'user@spoku.test'],
            [
                'name' => 'Sample User',
                'phone' => '01444444444',
                'roll' => 'A-101',
                'role' => UserRole::User,
                'is_active' => true,
                'password' => Hash::make('password'),
            ]
        );
    }
}
