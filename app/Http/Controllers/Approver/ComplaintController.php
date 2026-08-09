<?php

namespace App\Http\Controllers\Approver;

use App\Enums\ApprovalDecision;
use App\Enums\ComplaintStatus;
use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintApproval;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ComplaintController extends Controller
{
    public function index(): View
    {
        $complaints = Complaint::with(['person', 'user', 'approvals'])
            ->whereIn('status', [ComplaintStatus::Pending, ComplaintStatus::Published, ComplaintStatus::Rejected])
            ->latest()
            ->paginate(20);

        return view('approver.complaints.index', compact('complaints'));
    }

    public function show(Complaint $complaint): View
    {
        $complaint->load(['person', 'user', 'files', 'approvals.user']);
        $myDecision = $complaint->approvals->firstWhere('user_id', auth()->id());

        return view('approver.complaints.show', compact('complaint', 'myDecision'));
    }

    public function decide(Request $request, Complaint $complaint): RedirectResponse
    {
        $data = $request->validate([
            'decision' => ['required', 'in:approved,rejected'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        ComplaintApproval::updateOrCreate(
            [
                'complaint_id' => $complaint->id,
                'user_id' => $request->user()->id,
            ],
            [
                'decision' => ApprovalDecision::from($data['decision']),
                'notes' => $data['notes'] ?? null,
            ]
        );

        if ($complaint->status !== ComplaintStatus::Published) {
            $complaint->update(['status' => ComplaintStatus::Pending]);
        }

        $complaint->refreshPublicationStatus();

        return back()->with('status', 'Your decision has been recorded.');
    }
}
