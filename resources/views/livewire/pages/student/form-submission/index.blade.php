<form wire:submit.prevent="save" class="container mx-auto p-4 flex-grow flex flex-col gap-4">
    <div class="flex flex-col gap-4 justify-center bg-white shadow-md rounded-lg p-6 mb-4 ">
        <h2 class="text-2xl">{{ $formSubmissionPeriod->name }}
            ({{ $formSubmissionPeriod->semester }})</h2>
        <h2 class="text-xl">{{ $studentSubject->subject_name }} ({{ $studentSubject->subject_code }})
        </h2>
        @isset($this->teacher)
            <h2 class="text-xl">{{ $teacher->name }}</h2>
            <input type="hidden" name="teacher_id" value="{{ $teacher->id }}" wire:model="form.teacher_id" />
        @else
            <div class="mb-4 max-w-sm">
                <label for="form.teacher_id" class="block text-gray-700">Teacher</label>
                <select name="form.teacher_id" id="form.teacher_id" required class="w-full px-3 py-2 border rounded-lg"
                    wire:model="form.teacher_id">
                    <option value="" selected>Select a teacher</option>
                    @forelse ($teachers as $teacher)
                        <option value="{{ $teacher->id }}">{{ $teacher->user->name }}</option>
                    @empty
                        <option value="" selected disabled>No teachers found</option>
                    @endforelse
                </select>
                @error('form.teacher_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        @endisset
    </div>
    <x-forms.form-submission-form :form="$formModel" :readonly="false" :createWireModel="$this->getCreateWireModel()" />
    <div class="flex justify-end mb-16">
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Submit</button>
    </div>
</form>
