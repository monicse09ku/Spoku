<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs uppercase tracking-[0.2em] text-ink-mute">Our people</p>
            <h2 class="font-display text-3xl text-ink mt-1">Team</h2>
            <p class="mt-2 text-sm text-ink-mute">Approvers and support staff who review and assist with reports.</p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            @foreach($groups as $group)
                <section>
                    <h3 class="font-display text-2xl text-ink mb-6">{{ $group['label'] }}</h3>

                    @if($group['members']->isEmpty())
                        <p class="text-sm text-ink-mute">No members listed yet.</p>
                    @else
                        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach($group['members'] as $member)
                                <x-staff-card :user="$member" :show-role="true" />
                            @endforeach
                        </div>
                    @endif
                </section>
            @endforeach
        </div>
    </div>
</x-app-layout>
