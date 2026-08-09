<?php

namespace App\Http\Controllers;

use App\Enums\ComplaintStatus;
use App\Enums\ReactionType;
use App\Models\Complaint;
use App\Models\Reaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicComplaintController extends Controller
{
    public function index(): View
    {
        $complaints = Complaint::with(['person', 'reactions'])
            ->withCount('reactions')
            ->published()
            ->latest('published_at')
            ->paginate(12);

        return view('public.complaints.index', compact('complaints'));
    }

    public function show(Complaint $complaint): View
    {
        abort_unless($complaint->status === ComplaintStatus::Published, 404);

        $complaint->load(['person', 'files', 'reactions']);
        $reactionCounts = $complaint->reactions
            ->groupBy(fn (Reaction $r) => $r->type->value)
            ->map->count();

        $userReaction = auth()->check()
            ? $complaint->reactions->firstWhere('user_id', auth()->id())
            : null;

        return view('public.complaints.show', [
            'complaint' => $complaint,
            'reactionCounts' => $reactionCounts,
            'userReaction' => $userReaction,
            'reactionTypes' => ReactionType::cases(),
        ]);
    }

    public function react(Request $request, Complaint $complaint): RedirectResponse
    {
        abort_unless($complaint->status === ComplaintStatus::Published, 404);

        $data = $request->validate([
            'type' => ['required', 'in:'.implode(',', array_column(ReactionType::cases(), 'value'))],
        ]);

        Reaction::updateOrCreate(
            [
                'complaint_id' => $complaint->id,
                'user_id' => $request->user()->id,
            ],
            [
                'type' => ReactionType::from($data['type']),
            ]
        );

        return back()->with('status', 'Reaction recorded.');
    }
}
