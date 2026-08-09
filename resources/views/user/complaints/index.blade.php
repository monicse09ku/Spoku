<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center gap-4">
            <div>
                <h2 class="font-display text-3xl text-ink">My complaints</h2>
                <p class="mt-1 text-sm text-ink-mute">Track the status of reports you have filed.</p>
            </div>
            <a href="{{ route('user.complaints.create') }}" class="spoku-btn-primary">New complaint</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 border border-trust/20 bg-trust-soft text-trust px-4 py-3 text-sm">{{ session('status') }}</div>
            @endif

            <div class="spoku-panel overflow-hidden">
                <table class="spoku-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Person</th>
                            <th>Status</th>
                            <th>Anonymous</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($complaints as $complaint)
                            <tr>
                                <td class="font-medium">{{ $complaint->title }}</td>
                                <td>{{ $complaint->person->name }}</td>
                                <td><x-status-badge :status="$complaint->status" /></td>
                                <td>{{ $complaint->is_anonymous ? 'Yes' : 'No' }}</td>
                                <td class="text-right"><a class="text-trust font-semibold" href="{{ route('user.complaints.show', $complaint) }}">View</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-10 text-center text-ink-mute">You have not filed any complaints yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $complaints->links() }}</div>
        </div>
    </div>
</x-app-layout>
