<div class="contents">
    <div class="top flex justify-end mb-4">
        <button wire:click='openForm' id="addButton" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
            Add Section
        </button>
    </div>

    <!-- Main Dashboard Content -->
    <div class="main-dash grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Users and Completed Tasks -->

        <!-- Responsive Table -->
        <div class="col-span-1 md:col-span-3 overflow-auto">
            <table class="min-w-full bg-white rounded-lg overflow-hidden shadow-md table-fixed table">
                <thead class="bg-gray-200 text-gray-600">
                    <tr>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Year Level</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Semester</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Section Name</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Section Code</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Course</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse($sections as $section)
                        <tr wire:key="{{ $section->id }}">
                            <td class="py-3 px-4 border-b">
                                {{ $section->year_level }}
                            </td>
                            <td class="py-3 px-4 border-b">
                                {{ $section->semester }}
                            </td>
                            <td class="py-3 px-4 border-b">
                                {{ $section->code }}
                            </td>
                            <td class="py-3 px-4 border-b">
                                {{ $section->name }}
                            </td>
                            <td class="py-3 px-4 border-b">
                                {{ $section->course->name }}
                            </td>
                            <td class="py-3 px-4 text-right border-b">
                                <button wire:click='edit({{ $section->id }})'
                                    class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                                    Edit
                                </button>
                                @if ($section->hasDependents())
                                    @isset($section->archived_at)
                                        <button wire:click='unarchive({{ $section->id }})'
                                            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                                            Unarchive
                                        </button>
                                    @else
                                        <button wire:click='archive({{ $section->id }})'
                                            class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600"
                                            title="This department has courses associated with it. You can only archive it until you delete those courses.">
                                            Archive
                                        </button>
                                    @endisset
                                @else
                                    <button wire:click='delete({{ $section->id }})'
                                        wire:confirm='Are you sure you want to delete this section?'
                                        class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">
                                        Delete
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-3 px-4 text-center text-gray-500">
                                No Sections Found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($this->isFormOpen)
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center"
            wire:click.self='closeForm'>
            <div class="bg-white p-6 rounded-lg w-96">
                @isset($this->model)
                    <h3 class="text-lg font-semibold mb-4">Edit Section</h3>
                @else
                    <h3 class="text-lg font-semibold mb-4">Add New Section</h3>
                @endisset
                <form id="addForm" wire:submit.prevent='save'>
                    @csrf
                    <input type="hidden" name="id" wire:model.blur="form.id">
                    <div class="mb-4">
                        <label for="form.year_level" class="block text-gray-700">Year Level</label>
                        <input name="form.name" id="form.year_level" required class="w-full px-3 py-2 border rounded-lg"
                            type="number" wire:model.defer="form.year_level">
                        @error('form.year_level')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="form.semester" class="block text-gray-700">Semester</label>
                        <input name="form.semester" id="form.semester" required
                            class="w-full px-3 py-2 border rounded-lg" type="number" wire:model.blur="form.semester">
                        @error('form.semester')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="form.name" class="block text-gray-700">Name</label>
                        <input type="text" name="form.name" id="form.name" required
                            class="w-full px-3 py-2 border rounded-lg" wire:model.blur="form.name">
                        @error('form.name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="form.course_id" class="block text-gray-700">Course</label>
                        <select type="text" name="form.course_id" id="form.course_id" required
                            class="w-full px-3 py-2 border rounded-lg" wire:model.blur="form.course_id">
                            <option value="" selected>Select a course</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}">
                                    {{ $course->name }}
                                    ({{ $course->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('form.course_id')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="form.code" class="block text-gray-700">Section Code</label>
                        <input type="text" name="form.code" id="form.code" required
                            class="w-full px-3 py-2 border rounded-lg" wire:model.defer="form.code">
                        @error('form.code')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex justify-end">
                        <button type="button" id="cancelButton"
                            class="px-4 py-2 mr-2 text-gray-500 hover:text-gray-700"
                            wire:click='closeForm'>Cancel</button>
                        <button type="submit"
                            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Save</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
