@php
    use Illuminate\Support\Number;
@endphp
<div class="contents">
    <x-layouts.loader>
        <div class="container mx-auto p-4">
            <div class="flex justify-between mb-4">
                <h1 class="text-xl font-bold">{{ $course->name }} - Semesters</h1>
                <x-button wire:click='openCourseSemesterForm'>
                    Add Semester
                </x-button>
            </div>
            <x-accordion wire:key="course-semesters-list">
                @forelse ($courseSemesters as $semester)
                    <x-accordion.item :key="$semester->id">
                        <x-accordion.item-header>
                            <h2 class="text-xl" x-on:click="toggle()">
                                {{ Number::ordinal($semester->year_level) }} Year -
                                {{ Number::ordinal($semester->semester) }}
                                Semester
                                <span class="text-lg text-gray-400">({{ $semester->subjects_count }} Subject(s))</span>
                            </h2>
                            <div class="flex-grow"></div>
                            <div class="flex flex-row justify-end gap-1 items-center">
                                <x-button wire:click.stop='openAddCourseSubjectsForm({{ $semester->id }})'
                                    color="primary">
                                    Add Subjects
                                </x-button>

                                @if (!$semester->hasDependents())
                                    <x-button wire:click.stop='editCourseSemester({{ $semester->id }})'
                                        :disabled="$semester->hasDependents()" color="secondary">
                                        Edit
                                    </x-button>
                                    <x-button wire:click.stop='deleteCourseSemester({{ $semester->id }})'
                                        :disabled="$semester->hasDependents()" wire:confirm='Are you sure you want to delete this semester?'
                                        color="danger">
                                        Delete
                                    </x-button>
                                @endif
                            </div>
                        </x-accordion.item-header>
                        <x-accordion.item-body>
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
                        </x-accordion.item-body>
                    </x-accordion.item>
                @empty
                    <x-accordion.empty>
                        No Semesters Found
                    </x-accordion.empty>
                @endforelse
            </x-accordion>

        </div>

        <!-- Add Semester Modal -->
        @if ($isCourseSemesterFormOpen)
            <x-modal-scrim />
            <x-dialog.container>
                <x-dialog el="form" wire:submit.prevent="saveCourseSemester" wire:key="course-semester-form">
                    <x-dialog.title>
                        @isset($courseSemester)
                            Edit Semester
                        @else
                            Add New Semester
                        @endisset
                    </x-dialog.title>
                    <x-dialog.content>
                        @csrf
                        <input type="hidden" name="id" wire:model.defer="courseSemesterForm.id">
                        <input type="hidden" name="course_id" wire:model.defer="courseSemesterForm.course_id">
                        <x-form-control>
                            <x-form-control.label key="courseSemesterForm.year_level">Year Level</x-form-control.label>
                            <x-input key="courseSemesterForm.year_level" type="number"
                                wire:model="courseSemesterForm.year_level" required />
                            <x-form-control.error-text key="courseSemesterForm.year_level" />
                        </x-form-control>
                        <x-form-control>
                            <x-form-control.label key="semester">Semester</x-form-control.label>
                            <x-input key="semester" type="number" wire:model="courseSemesterForm.semester" required />
                            <x-form-control.error-text key="courseSemesterForm.semester" />
                        </x-form-control>

                        <x-form-control>
                            <x-form-control.label key="courseSemesterForm.subject_ids[]">Select
                                Subjects
                            </x-form-control.label>
                            <x-combobox id="subjectDropdown" name="courseSemesterForm.subject_ids[]" multiple
                                wire:model="courseSemesterForm.subject_ids" :options="$subjects" :label="fn($subject) => $subject->name . ' (' . $subject->code . ')'"
                                placeholder="Select Subjects" :value="fn($subject) => $subject->id" empty="No Subjects Found" />
                            <x-form-control.error-text key="courseSemesterForm.subject_ids" />
                            <x-form-control.error-text key="courseSemesterForm.subject_ids.*" />
                        </x-form-control>

                    </x-dialog.content>
                    <x-dialog.actions>
                        <x-button type="button" id="cancelBtn" wire:click='closeCourseSemesterForm' color="neutral"
                            variant="text">Cancel</x-button>
                        <x-button type="submit">Save</x-button>
                    </x-dialog.actions>
                </x-dialog>
            </x-dialog.container>
        @endif

        @if ($isAddCourseSubjectsFormOpen)
            <x-modal-scrim />
            <x-dialog.container>
                <x-dialog el="form" wire:submit="saveAddCourseSubjects">
                    <x-dialog.title>
                        Add Subjects
                    </x-dialog.title>
                    <x-dialog.content>
                        @csrf
                        <input type="hidden" name="addCourseSubjectsForm.course_semester_id"
                            wire:model.defer="addCourseSubjectsForm.course_semester_id">
                        <x-form-control>
                            <x-form-control.label key="addCourseSubjectsForm.subject_ids[]">Select
                                Subjects
                            </x-form-control.label>
                            <x-combobox id="subjectDropdown" name="addCourseSubjectsForm.subject_ids[]" multiple
                                wire:model="addCourseSubjectsForm.subject_ids" :options="$subjects" :label="fn($subject) => $subject->name . ' (' . $subject->code . ')'"
                                placeholder="Select Subjects" :value="fn($subject) => $subject->id" empty="No Subjects Found" />
                            <x-form-control.error-text key="addCourseSubjectsForm.subject_ids" />
                            <x-form-control.error-text key="addCourseSubjectsForm.subject_ids.*" />
                        </x-form-control>

                    </x-dialog.content>
                    <x-dialog.actions>
                        <x-button type="button" id="cancelBtn" wire:click='closeAddCourseSubjectsForm' color="neutral"
                            variant="text">Cancel</x-button>
                        <x-button type="submit">Save</x-button>
                    </x-dialog.actions>
                </x-dialog>
            </x-dialog.container>
        @endif
    </x-layouts.loader>
</div>
