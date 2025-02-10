@php
    use Illuminate\Support\Number;
@endphp

@assets
    <style>
        .accordion-toggle::before {
            transition: transform 0.3s ease-in-out;
            transform: rotate3d(0, 0, 1, 0deg);
        }

        .accordion-toggle-open::before {
            transform: rotate3d(0, 0, 1, 180deg);
        }

        .accordion-body {
            @supports (interpolate: allow-keywords) {
                interpolate-size: allow-keywords;
            }

            transition: transform 0.3s ease-in-out,
            height 0.3s ease-in-out;
            transform: scaleY(0);
            transform-origin: top;
            height: 0px;
        }

        .accordion-body-open {
            transform: scaleY(1);
            height: max-content;
        }
    </style>
@endassets

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
            <div class="text-gray-600 flex flex-row justify-between border border-gray-200 rounded-lg bg-white p-4
                    hover:bg-gray-100 cursor-pointer items-center"
                wire:click='toggleAccordion({{ $semester->id }})'>
                <h2 class="text-xl">
                    {{ Number::ordinal($semester->year_level) }} Year - {{ Number::ordinal($semester->semester) }}
                    Semester
                    <span class="text-lg text-gray-400">({{ $semester->subjects_count }} Subject(s))</span>
                </h2>
                <div class="flex flex-row justify-end gap-1 items-center">
                    <x-button wire:click.stop='openAddCourseSubjectsForm({{ $semester->id }})' color="primary">
                        Add Subjects
                    </x-button>
                    <x-button wire:click.stop='editCourseSemester({{ $semester->id }})' :disabled="$semester->hasDependents()"
                        color="secondary">
                        Edit
                    </x-button>
                    <x-button wire:click.stop='deleteCourseSemester({{ $semester->id }})' :disabled="$semester->hasDependents()"
                        wire:confirm='Are you sure you want to delete this semester?' color="danger">
                        Delete
                    </x-button>
                    <div>
                        <i
                            class="uil uil-angle-down accordion-toggle {{ $this->isOpenAccordion($semester) ? 'accordion-toggle-open' : '' }}"></i>
                    </div>
                </div>
            </div>
            <div class="accordion-body {{ $this->isOpenAccordion($semester) ? 'accordion-body-open' : '' }}">
                @php
                    $columns = [
                        ['label' => 'Subject Code', 'render' => 'subject.code'],
                        ['label' => 'Subject Name', 'render' => 'subject.name'],
                        [
                            'label' => 'Actions',
                            'render' => 'blade:table.actions',
                            'props' => [
                                'actions' => [
                                    'edit' => [
                                        'condition' => false,
                                    ],
                                    'archive' => [
                                        'wire:click' => fn($d) => "archiveCourseSubject({$d->id})",
                                    ],
                                    'unarchive' => [
                                        'wire:click' => fn($d) => "unarchiveCourseSubject({$d->id})",
                                    ],
                                    'delete' => [
                                        'wire:click' => fn($d) => "deleteCourseSubject({$d->id})",
                                    ],
                                ],
                            ],
                        ],
                    ];
                @endphp
                <x-table :data="$semester->courseSubjects()->lazy()" :columns="$columns">
                </x-table>
            </div>
        @empty
            <div
                class="text-xl text-gray-600 flex flex-row justify-center mb-2 border border-gray-200 rounded-lg bg-white p-4
                    items-center">
                <h2>
                    No Semesters Found
                </h2>
            </div>
        @endforelse
    </div>

    <!-- Add Semester Modal -->
    @if ($isCourseSemesterFormOpen)
        <div id="addSemesterModal" class="fixed inset-0 bg-black/50 flex justify-center items-center">
            <div class="bg-white p-6 rounded-lg w-96">
                @isset($courseSemester)
                    <h3 class="text-lg font-semibold mb-4">Edit Semester</h3>
                @else
                    <h3 class="text-lg font-semibold mb-4">Add New Semester</h3>
                @endisset

                <form id="addSemesterForm" wire:submit.prevent='saveCourseSemester'>
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
                    <div class="mb-4 {{ isset($courseSemester) ? 'hidden' : '' }}">
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
                        @error('courseSemesterForm.subject_ids.*')
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

    @if ($isAddCourseSubjectsFormOpen)
        <div id="addSemesterModal" class="fixed inset-0 bg-black/50 flex justify-center items-center">
            <div class="bg-white p-6 rounded-lg w-96">
                <h3 class="text-lg font-semibold mb-4">Add Subjects</h3>

                <form wire:submit.prevent='saveAddCourseSubjects'>
                    @csrf
                    <input type="hidden" name="addCourseSubjectsForm.course_semester_id"
                        wire:model.defer="addCourseSubjectsForm.course_semester_id">
                    <!-- Multi-Select Dropdown for Subjects -->
                    <div class="mb-4">
                        <label for="addCourseSubjectsForm.subject_ids[]" class="block text-gray-700">Select
                            Subjects</label>
                        <select name="addCourseSubjectsForm.subject_ids[]" multiple
                            wire:model.defer="addCourseSubjectsForm.subject_ids"
                            class="px-3 py-2 border rounded-lg w-full min-h-[300px]">
                            @foreach ($filteredSubjects as $subject)
                                <option value="{{ $subject->id }}">
                                    {{ $subject->name }}
                                    ({{ $subject->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('addCourseSubjectsForm.subject_ids')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                        @error('addCourseSubjectsForm.subject_ids.*')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-1">
                        <x-button type="button" id="cancelBtn" wire:click='closeAddCourseSubjectsForm' color="neutral"
                            variant="text">Cancel</x-button>
                        <x-button type="submit">Save</x-button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
