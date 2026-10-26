<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display text-3xl text-ink">Complaint detail</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 border border-trust/20 bg-trust-soft text-trust px-4 py-3 text-sm">{{ session('status') }}</div>
            @endif

            <div class="spoku-panel p-6 space-y-3">
                <h3 class="font-display text-2xl">{{ $complaint->title }}</h3>
                <p><strong>Person:</strong> {{ $complaint->person->name }}</p>
                <p><strong>Status:</strong> <x-status-badge :status="$complaint->status" /></p>
                <p><strong>Anonymous:</strong> {{ $complaint->is_anonymous ? 'Yes' : 'No' }}</p>
                <p><strong>Help requested:</strong>
                    @if(count($complaint->requestedHelpLabels()))
                        {{ implode(', ', $complaint->requestedHelpLabels()) }}
                    @else
                        None
                    @endif
                </p>
                @if(in_array($complaint->status, [\App\Enums\ComplaintStatus::AwaitingApprovers, \App\Enums\ComplaintStatus::ReadyToPublish, \App\Enums\ComplaintStatus::Published], true))
                    <p><strong>Approver votes:</strong> {{ $complaint->approvedCount() }} / {{ config('spoku.approval_threshold') }}</p>
                @endif
                <p class="whitespace-pre-line pt-2">{{ $complaint->details }}</p>

                @if($complaint->files->count())
                    <div class="grid gap-3 sm:grid-cols-2 mt-4">
                        @foreach($complaint->files as $file)
                            <div class="border border-paper-line p-3 text-sm">
                                @if($file->type === 'image')
                                    <img src="{{ asset('storage/'.$file->path) }}" class="mb-2 max-h-40 w-full object-cover" alt="">
                                @elseif($file->type === 'audio')
                                    <audio controls class="w-full" src="{{ asset('storage/'.$file->path) }}"></audio>
                                @else
                                    <video controls class="w-full" src="{{ asset('storage/'.$file->path) }}"></video>
                                @endif
                                {{ $file->original_name }}
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <a href="{{ route('user.complaints.index') }}" class="inline-block mt-4 text-sm text-ink-mute hover:text-ink">← Back</a>
        </div>
    </div>
</x-app-layout>
