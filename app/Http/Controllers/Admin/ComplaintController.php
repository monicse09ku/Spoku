<?php

namespace App\Http\Controllers\Admin;

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

    public function updateAssignments(Request $request, Complaint $complaint): RedirectResponse
    {
        $complaint->update([
            'assigned_to_lawyers' => $request->boolean('assigned_to_lawyers'),
            'assigned_to_counselors' => $request->boolean('assigned_to_counselors'),
            'assigned_to_law_enforcers' => $request->boolean('assigned_to_law_enforcers'),
            'assigned_to_humanitarians' => $request->boolean('assigned_to_humanitarians'),
        ]);

        return back()->with('status', 'Assignments updated.');
    }
}
