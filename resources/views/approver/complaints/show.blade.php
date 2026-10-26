<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Review Complaint</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="bg-green-50 text-green-800 p-3 rounded">{{ session('status') }}</div>
            @endif

            <div class="bg-white shadow rounded-lg p-6 space-y-3">
                <h3 class="text-xl font-bold">{{ $complaint->title }}</h3>
                <p><strong>Accused:</strong> {{ $complaint->person->name }}</p>
                <p><strong>Submitted by:</strong> {{ $complaint->submitterDisplayName() }}</p>
                <p class="whitespace-pre-line">{{ $complaint->details }}</p>
                @if($complaint->person->description)
                    <p class="text-gray-600">{{ $complaint->person->description }}</p>
                @endif

                @if($complaint->files->count())
                    <div class="grid gap-3 sm:grid-cols-2 mt-4">
                        @foreach($complaint->files as $file)
                            <div class="border rounded p-3 text-sm">
                                @if($file->type === 'image')
                                    <img src="{{ asset('storage/'.$file->path) }}" class="rounded mb-2 max-h-40 w-full object-cover" alt="">
                                @elseif($file->type === 'audio')
                                    <audio controls class="w-full" src="{{ asset('storage/'.$file->path) }}"></audio>
                                @else
                                    <video controls class="w-full rounded" src="{{ asset('storage/'.$file->path) }}"></video>
                                @endif
                                {{ $file->original_name }}
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h4 class="font-semibold mb-3">Your decision</h4>
                @if(! $canDecide)
                    <p class="text-sm text-gray-600">Voting is closed for this complaint (status: {{ $complaint->status->label() }}).</p>
                @else
                    @if($myDecision)
                        <p class="mb-3 text-sm text-gray-600">Current: <strong>{{ $myDecision->decision->value }}</strong> — you can update it below.</p>
                    @endif
                    <form method="POST" action="{{ route('approver.complaints.decide', $complaint) }}" class="space-y-4">
                        @csrf
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2"><input type="radio" name="decision" value="approved" required @checked(optional($myDecision)->decision?->value === 'approved')> Approve</label>
                            <label class="flex items-center gap-2"><input type="radio" name="decision" value="rejected" @checked(optional($myDecision)->decision?->value === 'rejected')> Reject</label>
                        </div>
                        <div>
                            <x-input-label for="notes" value="Notes (optional)" />
                            <textarea id="notes" name="notes" class="mt-1 w-full rounded border-gray-300" rows="3">{{ old('notes', $myDecision->notes ?? '') }}</textarea>
                        </div>
                        <x-primary-button>Submit decision</x-primary-button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
