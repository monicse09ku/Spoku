<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ComplaintStatus;
use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ComplaintController extends Controller
{
    public function index(Request $request): View
    {
        $complaints = Complaint::with(['person', 'user', 'approvals'])
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.complaints.index', compact('complaints'));
    }

    public function show(Complaint $complaint): View
    {
        $complaint->load(['person', 'user', 'files', 'approvals.user', 'reactions']);

        return view('admin.complaints.show', compact('complaint'));
    }

    public function sendToApprovers(Request $request, Complaint $complaint): RedirectResponse
    {
        abort_unless($complaint->status === ComplaintStatus::Pending, 403);

        $data = $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $complaint->update([
            'status' => ComplaintStatus::AwaitingApprovers,
            'admin_notes' => $data['admin_notes'] ?? $complaint->admin_notes,
        ]);

        return back()->with('status', 'Complaint sent to approvers.');
    }

    public function reject(Request $request, Complaint $complaint): RedirectResponse
    {
        abort_unless(in_array($complaint->status, [
            ComplaintStatus::Pending,
            ComplaintStatus::AwaitingApprovers,
            ComplaintStatus::ReadyToPublish,
        ], true), 403);

        $data = $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $complaint->update([
            'status' => ComplaintStatus::Rejected,
            'published_at' => null,
            'admin_notes' => $data['admin_notes'] ?? $complaint->admin_notes,
        ]);

        return back()->with('status', 'Complaint rejected.');
    }

    public function publish(Request $request, Complaint $complaint): RedirectResponse
    {
        abort_unless($complaint->status === ComplaintStatus::ReadyToPublish, 403);

        $data = $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:2000'],
            'assigned_to_lawyers' => ['nullable', 'boolean'],
            'assigned_to_counselors' => ['nullable', 'boolean'],
            'assigned_to_law_enforcers' => ['nullable', 'boolean'],
            'assigned_to_humanitarians' => ['nullable', 'boolean'],
        ]);

        $complaint->update([
            'status' => ComplaintStatus::Published,
            'published_at' => now(),
            'admin_notes' => $data['admin_notes'] ?? $complaint->admin_notes,
            'assigned_to_lawyers' => $request->boolean('assigned_to_lawyers'),
            'assigned_to_counselors' => $request->boolean('assigned_to_counselors'),
            'assigned_to_law_enforcers' => $request->boolean('assigned_to_law_enforcers'),
            'assigned_to_humanitarians' => $request->boolean('assigned_to_humanitarians'),
        ]);

        return back()->with('status', 'Complaint published and staff assigned.');
    }

    public function updateAssignments(Request $request, Complaint $complaint): RedirectResponse
    {
        abort_unless(in_array($complaint->status, [
            ComplaintStatus::ReadyToPublish,
            ComplaintStatus::Published,
        ], true), 403);

        $complaint->update([
            'assigned_to_lawyers' => $request->boolean('assigned_to_lawyers'),
            'assigned_to_counselors' => $request->boolean('assigned_to_counselors'),
            'assigned_to_law_enforcers' => $request->boolean('assigned_to_law_enforcers'),
            'assigned_to_humanitarians' => $request->boolean('assigned_to_humanitarians'),
        ]);

        return back()->with('status', 'Assignments updated.');
    }
}
