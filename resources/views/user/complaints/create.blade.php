<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-display text-3xl text-ink">File a complaint</h2>
            <p class="mt-1 text-sm text-ink-mute">Submit for admin review. You can also request help from specific staff teams.</p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('user.complaints.store') }}" enctype="multipart/form-data" class="spoku-panel p-6 sm:p-8 space-y-6" x-data="{ mode: '{{ old('person_mode', 'existing') }}' }">
                @csrf

                <div>
                    <x-input-label for="title" value="Complaint title" />
                    <x-text-input id="title" name="title" class="block mt-1 w-full" :value="old('title')" required />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="details" value="Complaint details" />
                    <textarea id="details" name="details" rows="5" class="spoku-input" required>{{ old('details') }}</textarea>
                    <x-input-error :messages="$errors->get('details')" class="mt-2" />
                </div>

                <label class="flex items-center gap-2 text-sm text-ink">
                    <input type="checkbox" name="is_anonymous" value="1" class="rounded border-paper-line text-signal focus:ring-signal" @checked(old('is_anonymous'))>
                    <span>Submit as anonymous</span>
                </label>

                <div class="border-t border-paper-line pt-6">
                    <p class="font-display text-xl mb-2">Request staff help</p>
                    <p class="text-sm text-ink-mute mb-4">Optional. Ask specific teams for support with this complaint.</p>
                    <div class="grid gap-3 sm:grid-cols-2 text-sm">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="help_requested_lawyers" value="1" class="rounded border-paper-line text-signal focus:ring-signal" @checked(old('help_requested_lawyers'))>
                            Lawyers
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="help_requested_counselors" value="1" class="rounded border-paper-line text-signal focus:ring-signal" @checked(old('help_requested_counselors'))>
                            Counselors
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="help_requested_law_enforcers" value="1" class="rounded border-paper-line text-signal focus:ring-signal" @checked(old('help_requested_law_enforcers'))>
                            Law Enforcers
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="help_requested_humanitarians" value="1" class="rounded border-paper-line text-signal focus:ring-signal" @checked(old('help_requested_humanitarians'))>
                            Humanitarians
                        </label>
                    </div>
                </div>

                <div class="border-t border-paper-line pt-6">
                    <p class="font-display text-xl mb-4">Person complained against</p>
                    <div class="flex gap-5 mb-4 text-sm">
                        <label class="flex items-center gap-2">
                            <input type="radio" name="person_mode" value="existing" x-model="mode" class="text-signal focus:ring-signal" @checked(old('person_mode', 'existing') === 'existing')>
                            Existing person
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="radio" name="person_mode" value="new" x-model="mode" class="text-signal focus:ring-signal" @checked(old('person_mode') === 'new')>
                            New person
                        </label>
                    </div>

                    <div x-show="mode === 'existing'">
                        <x-input-label for="person_id" value="Select person" />
                        <select id="person_id" name="person_id" class="spoku-input">
                            <option value="">Choose...</option>
                            @foreach($persons as $person)
                                <option value="{{ $person->id }}" @selected(old('person_id') == $person->id)>{{ $person->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('person_id')" class="mt-2" />
                    </div>

                    <div x-show="mode === 'new'" class="space-y-4">
                        <div>
                            <x-input-label for="person_name" value="Person name" />
                            <x-text-input id="person_name" name="person_name" class="block mt-1 w-full" :value="old('person_name')" />
                        </div>
                        <div>
                            <x-input-label for="person_description" value="Person description" />
                            <textarea id="person_description" name="person_description" rows="3" class="spoku-input">{{ old('person_description') }}</textarea>
                        </div>
                        <div>
                            <x-input-label for="person_image" value="Person image" />
                            <input id="person_image" type="file" name="person_image" accept="image/*" class="mt-2 block w-full text-sm text-ink-mute">
                        </div>
                    </div>
                </div>

                <div class="border-t border-paper-line pt-6">
                    <x-input-label for="files" value="Evidence files (image, audio, video)" />
                    <input id="files" type="file" name="files[]" multiple class="mt-2 block w-full text-sm text-ink-mute" accept="image/*,audio/*,video/*">
                    <x-input-error :messages="$errors->get('files')" class="mt-2" />
                    <x-input-error :messages="$errors->get('files.*')" class="mt-2" />
                </div>

                <div class="flex justify-end pt-2">
                    <x-primary-button>Submit complaint</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
