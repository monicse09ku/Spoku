<?php

namespace App\Http\Controllers\Staff;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssignedComplaintController extends Controller
{
    public function index(Request $request): View
    {
        $role = $request->user()->role;
        $column = match ($role) {
            UserRole::Lawyer => 'assigned_to_lawyers',
            UserRole::Counselor => 'assigned_to_counselors',
            UserRole::LawEnforcer => 'assigned_to_law_enforcers',
            default => abort(403),
        };

        $complaints = Complaint::with(['person', 'user'])
            ->where($column, true)
            ->latest()
            ->paginate(20);

        return view('staff.complaints.index', [
            'complaints' => $complaints,
            'roleLabel' => $role->label(),
        ]);
    }

    public function show(Request $request, Complaint $complaint): View
    {
        $role = $request->user()->role;
        $allowed = match ($role) {
            UserRole::Lawyer => $complaint->assigned_to_lawyers,
            UserRole::Counselor => $complaint->assigned_to_counselors,
            UserRole::LawEnforcer => $complaint->assigned_to_law_enforcers,
            default => false,
        };

        abort_unless($allowed, 403);

        $complaint->load(['person', 'user', 'files']);

        return view('staff.complaints.show', [
            'complaint' => $complaint,
            'roleLabel' => $role->label(),
        ]);
    }
}
