<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add {{ $role->label() }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.staff.store', $roleSlug) }}" class="bg-white shadow rounded-lg p-6 space-y-4">
                @csrf
                <div>
                    <x-input-label for="name" value="Name" />
                    <x-text-input id="name" name="name" class="block mt-1 w-full" :value="old('name')" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="email" value="Email" />
                    <x-text-input id="email" type="email" name="email" class="block mt-1 w-full" :value="old('email')" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="phone" value="Phone" />
                    <x-text-input id="phone" name="phone" class="block mt-1 w-full" :value="old('phone')" />
                </div>
                <div>
                    <x-input-label for="contact_info" value="Contact info" />
                    <x-text-input id="contact_info" name="contact_info" class="block mt-1 w-full" :value="old('contact_info')" />
                </div>
                <div>
                    <x-input-label for="password" value="Password" />
                    <x-text-input id="password" type="password" name="password" class="block mt-1 w-full" required />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="password_confirmation" value="Confirm password" />
                    <x-text-input id="password_confirmation" type="password" name="password_confirmation" class="block mt-1 w-full" required />
                </div>
                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.staff.index', $roleSlug) }}" class="text-sm text-gray-600 underline self-center">Cancel</a>
                    <x-primary-button>Create</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
