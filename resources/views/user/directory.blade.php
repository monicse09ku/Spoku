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
                'Humanitarians' => $humanitarians,
            ] as $label => $people)
                <section>
                    <h3 class="font-display text-2xl mb-6">{{ $label }}</h3>
                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @forelse($people as $person)
                            <x-staff-card :user="$person" :show-role="true" :show-contact="true" />
                        @empty
                            <p class="text-ink-mute text-sm">No contacts listed.</p>
                        @endforelse
                    </div>
                </section>
            @endforeach
        </div>
    </div>
</x-app-layout>
