@php
    use Illuminate\Support\Number;
@endphp

<div class="contents">
    <x-sections.header title="Sections">
        <x-button id="addCourseBtn" wire:click='openForm'>
            Add Section
        </x-button>
    </x-sections.header>

    <!-- Main Dashboard Content -->
    <div class="main-dash grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Users and Completed Tasks -->

        <!-- Responsive Table -->
        <div class="col-span-1 md:col-span-3 overflow-auto">
            @php
                $columns = [
                    ['label' => 'Year Level', 'render' => fn($data) => Number::ordinal($data->year_level)],
                    ['label' => 'Semester', 'render' => fn($data) => Number::ordinal($data->semester)],
                    ['label' => 'Section Name', 'render' => 'name'],
                    ['label' => 'Section Code', 'render' => 'code'],
                    ['label' => 'Course', 'render' => 'course.name'],
                    ['label' => 'Actions', 'render' => 'blade:table.actions'],
                ];
                $paginate = [
                    'perPage' => 15,
                ];
            @endphp
            <x-table :data="$sections" :$columns :$paginate>
            </x-table>
        </div>
    </div>

    @if ($isFormOpen)
        <div class="fixed inset-0 bg-gray-900/50 flex justify-center items-center" wire:click.self='closeForm'>
            <div class="bg-white p-6 rounded-lg w-96">
                @isset($model)
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
                    <div class="flex justify-end gap-1">
                        <x-button type="button" id="cancelBtn" wire:click='closeForm' color="neutral"
                            variant="text">Cancel</x-button>
                        <x-button type="submit">Save</x-button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
