<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'approvers' => User::where('role', UserRole::Approver)->count(),
                'lawyers' => User::where('role', UserRole::Lawyer)->count(),
                'counselors' => User::where('role', UserRole::Counselor)->count(),
                'law_enforcers' => User::where('role', UserRole::LawEnforcer)->count(),
                'humanitarians' => User::where('role', UserRole::Humanitarian)->count(),
                'users' => User::where('role', UserRole::User)->count(),
                'complaints' => Complaint::count(),
                'pending' => Complaint::where('status', 'pending')->count(),
                'published' => Complaint::where('status', 'published')->count(),
            ],
        ]);
    }
}
