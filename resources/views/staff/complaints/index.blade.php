<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $roleLabel }} Complaints</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-left">
                        <tr>
                            <th class="px-4 py-3">Title</th>
                            <th class="px-4 py-3">Person</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($complaints as $complaint)
                            <tr class="border-t">
                                <td class="px-4 py-3">{{ $complaint->title }}</td>
                                <td class="px-4 py-3">{{ $complaint->person->name }}</td>
                                <td class="px-4 py-3">{{ $complaint->status->label() }}</td>
                                <td class="px-4 py-3 text-right">
                                    @php
                                        $showRoute = match(auth()->user()->role) {
                                            \App\Enums\UserRole::Lawyer => 'lawyer.complaints.show',
                                            \App\Enums\UserRole::Counselor => 'counselor.complaints.show',
                                            default => 'law-enforcer.complaints.show',
                                        };
                                    @endphp
                                    <a class="text-indigo-600" href="{{ route($showRoute, $complaint) }}">Open</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">No complaints assigned to your role yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $complaints->links() }}</div>
        </div>
    </div>
</x-app-layout>
