<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $user = $request->user();

        return match ($user->role) {
            UserRole::Admin => redirect()->route('admin.dashboard'),
            UserRole::Approver => redirect()->route('approver.complaints.index'),
            UserRole::Lawyer => redirect()->route('lawyer.complaints.index'),
            UserRole::Counselor => redirect()->route('counselor.complaints.index'),
            UserRole::LawEnforcer => redirect()->route('law-enforcer.complaints.index'),
            UserRole::Humanitarian => redirect()->route('humanitarian.complaints.index'),
            default => redirect()->route('user.complaints.index'),
        };
    }
}
