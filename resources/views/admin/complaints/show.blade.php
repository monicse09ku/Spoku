<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Complaint #{{ $complaint->id }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="bg-green-50 text-green-800 p-3 rounded">{{ session('status') }}</div>
            @endif

            <div class="bg-white shadow rounded-lg p-6 space-y-3">
                <p><strong>Title:</strong> {{ $complaint->title }}</p>
                <p><strong>Person:</strong> {{ $complaint->person->name }}</p>
                <p><strong>Submitter:</strong> {{ $complaint->submitterDisplayName() }}</p>
                <p><strong>Status:</strong> {{ $complaint->status->label() }}</p>
                <p><strong>Approvals:</strong> {{ $complaint->approvedCount() }} approved / {{ $complaint->rejectedCount() }} rejected (need {{ config('spoku.approval_threshold') }})</p>
                <p class="whitespace-pre-line">{{ $complaint->details }}</p>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="font-semibold mb-3">Assign to staff roles</h3>
                <form method="POST" action="{{ route('admin.complaints.assignments', $complaint) }}" class="space-y-3">
                    @csrf @method('PATCH')
                    <label class="flex items-center gap-2"><input type="checkbox" name="assigned_to_lawyers" value="1" @checked($complaint->assigned_to_lawyers)> Lawyers</label>
                    <label class="flex items-center gap-2"><input type="checkbox" name="assigned_to_counselors" value="1" @checked($complaint->assigned_to_counselors)> Counselors</label>
                    <label class="flex items-center gap-2"><input type="checkbox" name="assigned_to_law_enforcers" value="1" @checked($complaint->assigned_to_law_enforcers)> Law Enforcers</label>
                    <x-primary-button>Save assignments</x-primary-button>
                </form>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="font-semibold mb-3">Approver decisions</h3>
                <ul class="space-y-2 text-sm">
                    @forelse($complaint->approvals as $approval)
                        <li>{{ $approval->user->name }} — {{ $approval->decision->value }} @if($approval->notes) ({{ $approval->notes }}) @endif</li>
                    @empty
                        <li class="text-gray-500">No decisions yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
