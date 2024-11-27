<div class="contents">
    <div class="top flex justify-end mb-4">
        <button class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600" wire:click='openForm'>
            Add Submission Period
        </button>
    </div>

    <!-- Main Dashboard Content -->
    <div class="main-dash grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Users and Completed Tasks -->

        <!-- Responsive Table -->
        <div class="col-span-1 md:col-span-3 overflow-auto">
            <table class="min-w-full bg-white rounded-lg overflow-hidden shadow-md">
                <thead class="bg-gray-200 text-gray-600">
                    <tr>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Name</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Form</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Semester</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Start Date</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">End Date</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Open</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Submissions Editable</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse($formSubmissionPeriods as $period)
                        <tr>
                            <td class="py-3 px-4 border-b">{{ $period->name }}</td>
                            <td class="py-3 px-4 border-b">{{ $period->form->name }}</td>
                            <td class="py-3 px-4 border-b">{{ $period->semester }}</td>
                            <td class="py-3 px-4 border-b">{{ $period->starts_at }}</td>
                            <td class="py-3 px-4 border-b">{{ $period->ends_at }}</td>
                            <td class="py-3 px-4 border-b">{{ $period->is_open ? 'Yes' : 'No' }}</td>
                            <td class="py-3 px-4 border-b">{{ $period->is_submissions_editable ? 'Yes' : 'No' }}</td>
                            <td class="py-3 px-4 border-b">
                                <button wire:click='edit({{ $period->id }})'
                                    class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                                    Edit
                                </button>
                                @if ($period->hasDependents())
                                    @if ($period->is_archived)
                                        <button wire:click='unarchive({{ $period->id }})'
                                            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                                            Unarchive
                                        </button>
                                    @else
                                        <button wire:click='archive({{ $period->id }})'
                                            class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600"
                                            title="This period has courses associated with it. You can only archive it until you delete those courses.">
                                            Archive
                                        </button>
                                    @endif
                                @else
                                    <button wire:click='delete({{ $period->id }})'
                                        wire:confirm='Are you sure you want to delete this period?'
                                        class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">
                                        Delete
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-3 px-4 text-center text-gray-500">No submission periods found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Subject Modal -->
    @if ($this->isFormOpen)
        <div id="addSubjectModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center"
            wire:click.self='closeForm'>
            <div class="bg-white p-6 rounded-lg w-96">
                @isset($this->model)
                    <h3 class="text-lg font-semibold mb-4">Edit Submission Period</h3>
                @else
                    <h3 class="text-lg font-semibold mb-4">Add New Submission Period</h3>
                @endisset

                <!-- Add Subject Form -->
                <form wire:submit.prevent="save">
                    @csrf
                    <input type="hidden" name="id" wire:model.defer="form.id">
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700">Name</label>
                        <input type="text" name="name" id="name" required
                            class="w-full px-3 py-2 border rounded-lg" wire:model="form.name">
                        @error('form.name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="starts_at" class="block text-gray-700">Start Date</label>
                        <input type="datetime-local" name="starts_at" id="starts_at" required
                            class="w-full px-3 py-2 border rounded-lg" wire:model="form.starts_at">
                        @error('form.starts_at')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="ends_at" class="block text-gray-700">End Date</label>
                        <input type="datetime-local" name="ends_at" id="starts_at" required
                            class="w-full px-3 py-2 border rounded-lg" wire:model="form.ends_at">
                        @error('form.ends_at')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="ends_at" class="block text-gray-700">Semester</label>
                        <select name="ends_at" id="semester_id" required class="w-full px-3 py-2 border rounded-lg"
                            wire:model="form.semester_id">
                            <option value="">Select semester</option>
                            @forelse ($semesters as $semester)
                                <option value="{{ $semester->id }}">{{ $semester }}</option>
                            @empty
                                <option value="">No Semesters Available</option>
                            @endforelse
                        </select>
                        @error('form.semester_id')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="form_id" class="block text-gray-700">Form</label>
                        <select name="form_id" id="form_id" required class="w-full px-3 py-2 border rounded-lg"
                            wire:model="form.form_id">
                            <option value="">Select form</option>
                            @forelse ($forms as $form)
                                <option value="{{ $form->id }}">{{ $form->name }}</option>
                            @empty
                                <option value="">No Forms Available</option>
                            @endforelse
                        </select>
                        @error('form.form_id')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="is_open" class="block text-sm font-medium text-gray-700">Open</label>
                        <input type="checkbox" name="is_open" id="is_open" wire:model="form.is_open">
                        @error('form.is_open')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="is_submissions_editable"
                            class="block text-sm font-medium text-gray-700">Submissions
                            Editable</label>
                        <input type="checkbox" name="is_submissions_editable" id="is_submissions_editable"
                            wire:model="form.is_submissions_editable">
                        @error('form.is_submissions_editable')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex justify-end">
                        <button type="button" id="cancelBtn" wire:click='closeForm'
                            class="px-4 py-2 mr-2 text-gray-500 hover:text-gray-700">Cancel</button>
                        <button type="submit"
                            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Save</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
