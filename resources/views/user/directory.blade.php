<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-display text-3xl text-ink">Support directory</h2>
            <p class="mt-1 text-sm text-ink-mute">Contact information for approvers and support roles.</p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            @foreach([
                'Approvers' => $approvers,
                'Lawyers' => $lawyers,
                'Counselors' => $counselors,
                'Law Enforcers' => $law_enforcers,
            ] as $label => $people)
                <section>
                    <h3 class="font-display text-2xl mb-4">{{ $label }}</h3>
                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @forelse($people as $person)
                            <div class="border-b border-paper-line pb-4">
                                <div class="font-semibold text-ink">{{ $person->name }}</div>
                                <div class="text-sm text-ink-mute mt-1">{{ $person->email }}</div>
                                <div class="text-sm text-ink-mute">{{ $person->phone }}</div>
                                @if($person->contact_info)
                                    <div class="text-sm text-ink-soft mt-2">{{ $person->contact_info }}</div>
                                @endif
                            </div>
                        @empty
                            <p class="text-ink-mute text-sm">No contacts listed.</p>
                        @endforelse
                    </div>
                </section>
            @endforeach
        </div>
    </div>
</x-app-layout>
