<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $roleLabel }} — Complaint Detail</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6 space-y-3">
                <h3 class="text-xl font-bold">{{ $complaint->title }}</h3>
                <p><strong>Accused:</strong> {{ $complaint->person->name }}</p>
                <p><strong>Status:</strong> {{ $complaint->status->label() }}</p>
                <p><strong>Submitted by:</strong> {{ $complaint->submitterDisplayName() }}</p>
                <p class="whitespace-pre-line">{{ $complaint->details }}</p>

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
        </div>
    </div>
</x-app-layout>
