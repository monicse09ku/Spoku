<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-display text-3xl text-ink">Complaints for approval</h2>
            <p class="mt-1 text-sm text-ink-mute">Complaints appear here after admin forwards them. {{ config('spoku.approval_threshold') }} approvals needed before admin can publish.</p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="spoku-panel overflow-hidden">
                <table class="spoku-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Person</th>
                            <th>Status</th>
                            <th>Votes</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($complaints as $complaint)
                            <tr>
                                <td class="font-medium">{{ $complaint->title }}</td>
                                <td>{{ $complaint->person->name }}</td>
                                <td><x-status-badge :status="$complaint->status" /></td>
                                <td>{{ $complaint->approvedCount() }} yes / {{ $complaint->rejectedCount() }} no</td>
                                <td class="text-right"><a class="text-trust font-semibold" href="{{ route('approver.complaints.show', $complaint) }}">Review</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $complaints->links() }}</div>
        </div>
    </div>
</x-app-layout>
