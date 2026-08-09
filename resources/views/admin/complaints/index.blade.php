<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-display text-3xl text-ink">Complaint management</h2>
            <p class="mt-1 text-sm text-ink-mute">Review status and assign reports to staff roles.</p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form class="mb-6 flex flex-wrap gap-3 items-center">
                <select name="status" class="spoku-input !w-auto !mt-0">
                    <option value="">All statuses</option>
                    @foreach(\App\Enums\ComplaintStatus::cases() as $status)
                        <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->label() }}</option>
                    @endforeach
                </select>
                <x-primary-button>Filter</x-primary-button>
            </form>

            <div class="spoku-panel overflow-hidden">
                <table class="spoku-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Person</th>
                            <th>Status</th>
                            <th>Approvals</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($complaints as $complaint)
                            <tr>
                                <td class="font-medium">{{ $complaint->title }}</td>
                                <td>{{ $complaint->person->name }}</td>
                                <td><x-status-badge :status="$complaint->status" /></td>
                                <td>{{ $complaint->approvedCount() }} / {{ config('spoku.approval_threshold') }}</td>
                                <td class="text-right"><a href="{{ route('admin.complaints.show', $complaint) }}" class="text-trust font-semibold">View</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $complaints->links() }}</div>
        </div>
    </div>
</x-app-layout>
