<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="font-display text-3xl text-ink">Complaint #{{ $complaint->id }}</h2>
                <p class="mt-1 text-sm text-ink-mute">Review, forward to approvers, publish, and assign staff.</p>
            </div>
            <x-status-badge :status="$complaint->status" />
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="border border-trust/20 bg-trust-soft text-trust px-4 py-3 text-sm">{{ session('status') }}</div>
            @endif

            <div class="spoku-panel p-6 space-y-3">
                <p><strong>Title:</strong> {{ $complaint->title }}</p>
                <p><strong>Person:</strong> {{ $complaint->person->name }}</p>
                <p><strong>Submitter:</strong> {{ $complaint->submitterDisplayName() }}</p>
                <p><strong>Status:</strong> {{ $complaint->status->label() }}</p>
                <p><strong>Approver votes:</strong> {{ $complaint->approvedCount() }} approved / {{ $complaint->rejectedCount() }} rejected (need {{ config('spoku.approval_threshold') }})</p>
                <p><strong>Help requested:</strong>
                    @if(count($complaint->requestedHelpLabels()))
                        {{ implode(', ', $complaint->requestedHelpLabels()) }}
                    @else
                        None
                    @endif
                </p>
                <p class="whitespace-pre-line pt-2">{{ $complaint->details }}</p>
            </div>

            @if($complaint->status === \App\Enums\ComplaintStatus::Pending)
                <div class="spoku-panel p-6 space-y-4">
                    <h3 class="font-display text-xl">Admin screening</h3>
                    <p class="text-sm text-ink-mute">Send this complaint to approvers, or reject it now.</p>
                    <div>
                        <x-input-label for="admin_notes" value="Admin notes (optional)" />
                        <textarea id="admin_notes" name="admin_notes" form="admin-screen-form" class="spoku-input" rows="3">{{ old('admin_notes', $complaint->admin_notes) }}</textarea>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <form id="admin-screen-form" method="POST" action="{{ route('admin.complaints.send-to-approvers', $complaint) }}">
                            @csrf
                            <x-primary-button>Send to Approvers</x-primary-button>
                        </form>
                        <form method="POST" action="{{ route('admin.complaints.reject', $complaint) }}" onsubmit="return confirm('Reject this complaint?')">
                            @csrf
                            <input type="hidden" name="admin_notes" id="reject_notes_mirror">
                            <button type="submit" class="spoku-btn-secondary" onclick="document.getElementById('reject_notes_mirror').value = document.getElementById('admin_notes').value">Reject</button>
                        </form>
                    </div>
                </div>
            @endif

            @if($complaint->status === \App\Enums\ComplaintStatus::ReadyToPublish)
                <div class="spoku-panel p-6 space-y-4">
                    <h3 class="font-display text-xl">Publish & assign staff</h3>
                    <p class="text-sm text-ink-mute">Approvers reached the threshold. Publish to the public feed and assign support staff.</p>
                    <form method="POST" action="{{ route('admin.complaints.publish', $complaint) }}" class="space-y-4">
                        @csrf
                        <div>
                            <x-input-label for="publish_notes" value="Admin notes (optional)" />
                            <textarea id="publish_notes" name="admin_notes" class="spoku-input" rows="3">{{ old('admin_notes', $complaint->admin_notes) }}</textarea>
                        </div>
                        <div class="space-y-2 text-sm">
                            <p class="font-semibold">Assign staff</p>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="assigned_to_lawyers" value="1" @checked(old('assigned_to_lawyers', $complaint->assigned_to_lawyers || $complaint->help_requested_lawyers))>
                                Lawyers @if($complaint->help_requested_lawyers)<span class="text-ink-mute">(requested by user)</span>@endif
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="assigned_to_counselors" value="1" @checked(old('assigned_to_counselors', $complaint->assigned_to_counselors || $complaint->help_requested_counselors))>
                                Counselors @if($complaint->help_requested_counselors)<span class="text-ink-mute">(requested by user)</span>@endif
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="assigned_to_law_enforcers" value="1" @checked(old('assigned_to_law_enforcers', $complaint->assigned_to_law_enforcers || $complaint->help_requested_law_enforcers))>
                                Law Enforcers @if($complaint->help_requested_law_enforcers)<span class="text-ink-mute">(requested by user)</span>@endif
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="assigned_to_humanitarians" value="1" @checked(old('assigned_to_humanitarians', $complaint->assigned_to_humanitarians || $complaint->help_requested_humanitarians))>
                                Humanitarians @if($complaint->help_requested_humanitarians)<span class="text-ink-mute">(requested by user)</span>@endif
                            </label>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <x-primary-button>Publish complaint</x-primary-button>
                        </div>
                    </form>
                    <form method="POST" action="{{ route('admin.complaints.reject', $complaint) }}" onsubmit="return confirm('Reject this complaint after approver approval?')">
                        @csrf
                        <button type="submit" class="spoku-btn-ghost text-signal">Reject instead</button>
                    </form>
                </div>
            @endif

            @if(in_array($complaint->status, [\App\Enums\ComplaintStatus::Published, \App\Enums\ComplaintStatus::ReadyToPublish], true))
                <div class="spoku-panel p-6">
                    <h3 class="font-display text-xl mb-3">Update staff assignments</h3>
                    <form method="POST" action="{{ route('admin.complaints.assignments', $complaint) }}" class="space-y-3">
                        @csrf @method('PATCH')
                        <label class="flex items-center gap-2"><input type="checkbox" name="assigned_to_lawyers" value="1" @checked($complaint->assigned_to_lawyers)> Lawyers</label>
                        <label class="flex items-center gap-2"><input type="checkbox" name="assigned_to_counselors" value="1" @checked($complaint->assigned_to_counselors)> Counselors</label>
                        <label class="flex items-center gap-2"><input type="checkbox" name="assigned_to_law_enforcers" value="1" @checked($complaint->assigned_to_law_enforcers)> Law Enforcers</label>
                        <label class="flex items-center gap-2"><input type="checkbox" name="assigned_to_humanitarians" value="1" @checked($complaint->assigned_to_humanitarians)> Humanitarians</label>
                        <x-primary-button>Save assignments</x-primary-button>
                    </form>
                </div>
            @endif

            @if($complaint->status === \App\Enums\ComplaintStatus::AwaitingApprovers)
                <div class="spoku-panel p-6">
                    <h3 class="font-display text-xl mb-2">Waiting on approvers</h3>
                    <p class="text-sm text-ink-mute mb-4">{{ $complaint->approvedCount() }} / {{ config('spoku.approval_threshold') }} approvals so far.</p>
                    <form method="POST" action="{{ route('admin.complaints.reject', $complaint) }}" onsubmit="return confirm('Reject this complaint?')">
                        @csrf
                        <button type="submit" class="spoku-btn-secondary">Reject complaint</button>
                    </form>
                </div>
            @endif

            <div class="spoku-panel p-6">
                <h3 class="font-display text-xl mb-3">Approver decisions</h3>
                <ul class="space-y-2 text-sm">
                    @forelse($complaint->approvals as $approval)
                        <li>{{ $approval->user->name }} — {{ $approval->decision->value }} @if($approval->notes) ({{ $approval->notes }}) @endif</li>
                    @empty
                        <li class="text-ink-mute">No decisions yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
