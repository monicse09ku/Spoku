<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-display text-3xl text-ink">Edit {{ $role->label() }}</h2>
            <p class="mt-1 text-sm text-ink-mute">Update account details and profile image.</p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.staff.update', [$roleSlug, $user]) }}" enctype="multipart/form-data" class="spoku-panel p-6 sm:p-8 space-y-4">
                @csrf @method('PUT')
                <div>
                    <x-input-label for="name" value="Name" />
                    <x-text-input id="name" name="name" class="block mt-1 w-full" :value="old('name', $user->name)" required />
                </div>
                <div>
                    <x-input-label for="email" value="Email" />
                    <x-text-input id="email" type="email" name="email" class="block mt-1 w-full" :value="old('email', $user->email)" required />
                </div>
                <div>
                    <x-input-label for="phone" value="Phone" />
                    <x-text-input id="phone" name="phone" class="block mt-1 w-full" :value="old('phone', $user->phone)" />
                </div>
                <div>
                    <x-input-label for="contact_info" value="Contact info" />
                    <x-text-input id="contact_info" name="contact_info" class="block mt-1 w-full" :value="old('contact_info', $user->contact_info)" />
                </div>
                <div>
                    <x-input-label for="image" value="Profile image" />
                    @if($user->imageUrl())
                        <img src="{{ $user->imageUrl() }}" alt="{{ $user->name }}" class="mt-2 w-24 h-24 object-cover">
                    @endif
                    <input id="image" type="file" name="image" accept="image/*" class="mt-2 block w-full text-sm text-ink-mute">
                    <p class="mt-1 text-xs text-ink-mute">Upload a new image to replace the current one.</p>
                    <x-input-error :messages="$errors->get('image')" class="mt-2" />
                </div>
                <div class="flex items-center gap-2">
                    <input id="is_active" type="checkbox" name="is_active" value="1" class="rounded border-paper-line text-signal focus:ring-signal" @checked(old('is_active', $user->is_active))>
                    <x-input-label for="is_active" value="Active" />
                </div>
                <div>
                    <x-input-label for="password" value="New password (optional)" />
                    <x-text-input id="password" type="password" name="password" class="block mt-1 w-full" />
                </div>
                <div>
                    <x-input-label for="password_confirmation" value="Confirm password" />
                    <x-text-input id="password_confirmation" type="password" name="password_confirmation" class="block mt-1 w-full" />
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <a href="{{ route('admin.staff.index', $roleSlug) }}" class="spoku-btn-ghost self-center">Cancel</a>
                    <x-primary-button>Save</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
