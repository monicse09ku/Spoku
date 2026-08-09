<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center gap-4">
            <div>
                <h2 class="font-display text-3xl text-ink">{{ $role->label() }} management</h2>
                <p class="mt-1 text-sm text-ink-mute">Create and maintain {{ strtolower($role->label()) }} accounts.</p>
            </div>
            <a href="{{ route('admin.staff.create', $roleSlug) }}" class="spoku-btn-primary">Add {{ $role->label() }}</a>
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
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Active</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>
                                    @if($user->imageUrl())
                                        <img src="{{ $user->imageUrl() }}" alt="" class="w-10 h-10 object-cover">
                                    @else
                                        <div class="w-10 h-10 bg-ink/5 text-[10px] text-ink-mute flex items-center justify-center">N/A</div>
                                    @endif
                                </td>
                                <td class="font-medium">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ $user->is_active ? 'Yes' : 'No' }}</td>
                                <td class="text-right space-x-3">
                                    <a href="{{ route('admin.staff.edit', [$roleSlug, $user]) }}" class="text-trust font-semibold">Edit</a>
                                    <form action="{{ route('admin.staff.destroy', [$roleSlug, $user]) }}" method="POST" class="inline" onsubmit="return confirm('Delete this user?')">
                                        @csrf @method('DELETE')
                                        <button class="text-signal font-semibold">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $users->links() }}</div>
            <a href="{{ route('admin.dashboard') }}" class="inline-block mt-6 text-sm text-ink-mute hover:text-ink">← Back to admin</a>
        </div>
    </div>
</x-app-layout>
