<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display text-3xl text-ink">Admin control</h2>
        <p class="mt-1 text-sm text-ink-mute">Manage staff roles and complaint assignments.</p>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach([
                    'Approvers' => $stats['approvers'],
                    'Lawyers' => $stats['lawyers'],
                    'Counselors' => $stats['counselors'],
                    'Law Enforcers' => $stats['law_enforcers'],
                    'Users' => $stats['users'],
                    'Complaints' => $stats['complaints'],
                    'Pending' => $stats['pending'],
                    'Published' => $stats['published'],
                ] as $label => $value)
                    <div class="border-b border-paper-line pb-4">
                        <div class="text-xs uppercase tracking-[0.16em] text-ink-mute">{{ $label }}</div>
                        <div class="mt-2 font-display text-4xl text-ink">{{ $value }}</div>
                    </div>
                @endforeach
            </div>

            <div>
                <h3 class="font-display text-2xl mb-4">Modules</h3>
                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    <a class="spoku-panel p-5 hover:border-signal/40 transition" href="{{ route('admin.staff.index', 'approvers') }}">Manage Approvers</a>
                    <a class="spoku-panel p-5 hover:border-signal/40 transition" href="{{ route('admin.staff.index', 'lawyers') }}">Manage Lawyers</a>
                    <a class="spoku-panel p-5 hover:border-signal/40 transition" href="{{ route('admin.staff.index', 'counselors') }}">Manage Counselors</a>
                    <a class="spoku-panel p-5 hover:border-signal/40 transition" href="{{ route('admin.staff.index', 'law-enforcers') }}">Manage Law Enforcers</a>
                    <a class="spoku-panel p-5 hover:border-signal/40 transition" href="{{ route('admin.complaints.index') }}">Complaint Management</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
