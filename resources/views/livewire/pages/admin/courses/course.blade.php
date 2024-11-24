@php
    use Illuminate\Support\Number;
@endphp

<div class="contents">
    <div class="container mx-auto p-4">
        <div class="flex justify-between mb-4">
            <h1 class="text-xl font-bold">{{ $course->name }} - Semesters</h1>
            <button id="addSemesterBtn" wire:click='openCourseSemesterForm'
                class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                Add Semester
            </button>
        </div>

        @forelse ($courseSemesters as $semester)
            <div
                class="text-xl text-gray-600 flex flex-row justify-between mb-2 border border-gray-200 rounded-lg bg-white p-4
                    hover:bg-gray-100 cursor-pointer items-center">
                <h2>
                    {{ Number::ordinal($semester->year_level) }} Year - {{ Number::ordinal($semester->semester) }}
                    Semester
                </h2>
                <div class="flex flex-row justify-end gap-2">
                    <button wire:click='editCourseSemester({{ $semester->id }})'
                        class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                        Edit
                    </button>
                    <button wire:click='deleteCourseSemester({{ $semester->id }})'
                        wire:confirm='Are you sure you want to delete this semester?'
                        class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">
                        Delete
                    </button>
                </div>
            </div>
        @empty
        @endforelse
    </div>

    <!-- Add Semester Modal -->
    @if ($this->isCourseSemesterFormOpen)
        <div id="addSemesterModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
            <div class="bg-white p-6 rounded-lg w-96">
                @isset($this->courseSemester)
                    <h3 class="text-lg font-semibold mb-4">Edit Semester</h3>
                @else
                    <h3 class="text-lg font-semibold mb-4">Add New Semester</h3>
                @endisset

                <form id="addSemesterForm" wire:submit='saveCourseSemester'>
                    @csrf
                    <input type="hidden" name="id" wire:model.defer="courseSemesterForm.id">
                    <input type="hidden" name="course_id" wire:model.defer="courseSemesterForm.course_id">
                    <div class="mb-4">
                        <label for="courseSemesterForm.year_level" class="block text-gray-700">Year
                            Level</label>
                        <input type="number" name="courseSemesterForm.year_level" id="courseSemesterForm.year_level"
                            wire:model.defer="courseSemesterForm.year_level" required
                            class="w-full px-3 py-2 border rounded-lg">
                        @error('courseSemesterForm.year_level')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="semester" class="block text-gray-700">Semester</label>
                        <input type="number" name="semester" id="semester" required
                            class="w-full px-3 py-2 border rounded-lg" wire:model.defer="courseSemesterForm.semester">
                        @error('courseSemesterForm.semester')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Multi-Select Dropdown for Subjects -->
                    <div class="mb-4">
                        <label for="courseSemesterForm.subject_ids[]" class="block text-gray-700">Select
                            Subjects</label>
                        <select id="subjectDropdown" name="courseSemesterForm.subject_ids[]" multiple
                            wire:model.defer="courseSemesterForm.subject_ids"
                            class="px-3 py-2 border rounded-lg w-full min-h-[300px]">
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}">
                                    {{ $subject->name }}
                                    ({{ $subject->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('courseSemesterForm.subject_ids')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="button" id="cancelBtn" class="px-4 py-2 mr-2 text-gray-500 hover:text-gray-700"
                            wire:click='closeCourseSemesterForm'>Cancel</button>
                        <button type="submit"
                            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Save</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
