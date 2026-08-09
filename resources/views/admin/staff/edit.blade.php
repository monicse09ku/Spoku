<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit {{ $role->label() }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.staff.update', [$roleSlug, $user]) }}" class="bg-white shadow rounded-lg p-6 space-y-4">
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
                <div class="flex items-center gap-2">
                    <input id="is_active" type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->is_active))>
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
                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.staff.index', $roleSlug) }}" class="text-sm text-gray-600 underline self-center">Cancel</a>
                    <x-primary-button>Save</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
