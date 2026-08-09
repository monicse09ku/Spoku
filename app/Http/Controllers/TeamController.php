<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function __invoke(): View
    {
        $order = [
            UserRole::Approver->value,
            UserRole::Lawyer->value,
            UserRole::Counselor->value,
            UserRole::LawEnforcer->value,
        ];

        $staff = User::query()
            ->where('is_active', true)
            ->whereIn('role', $order)
            ->orderBy('name')
            ->get()
            ->sortBy(fn (User $user) => array_search($user->role->value, $order, true))
            ->groupBy(fn (User $user) => $user->role->value);

        return view('public.team', [
            'groups' => [
                'approver' => ['label' => 'Approvers', 'members' => $staff->get('approver', collect())],
                'lawyer' => ['label' => 'Lawyers', 'members' => $staff->get('lawyer', collect())],
                'counselor' => ['label' => 'Counselors', 'members' => $staff->get('counselor', collect())],
                'law_enforcer' => ['label' => 'Law Enforcers', 'members' => $staff->get('law_enforcer', collect())],
            ],
        ]);
    }
}
