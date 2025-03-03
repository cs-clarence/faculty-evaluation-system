@php
    use Illuminate\Support\Number;
@endphp


<div class="contents">
    <x-layouts.loader>
        <div class="container mx-auto p-4">
            <div class="flex justify-between mb-4">
                <h1 class="text-xl font-bold">{{ $teacher->user->name }} - Semesters</h1>
                <x-button wire:click='openSemesterForm'>
                    Add Semester
                </x-button>
            </div>

            <x-accordion>
                @forelse ($teacher->teacherSemesters as $semester)
                    <x-accordion.item :key="$semester->id">
                        <x-accordion.item-header>
                            <h2 class="text-xl" x-on:click="toggle()">
                                {{ Number::ordinal($semester->year_level) }} Year -
                                {{ $semester->semester }}
                                <span class="text-lg text-gray-400">({{ $semester->teacherSubjects()->count() }}
                                    Subject(s))
                                </span>
                            </h2>
                            <div class="flex-grow"></div>
                            <div class="flex flex-row justify-end gap-1 items-center">
                                <x-button wire:click.stop='openAddSubjectsForm({{ $semester->id }})' color="primary">
                                    Add Subjects
                                </x-button>

                                @if (!$semester->hasDependents())
                                    <x-button wire:click.stop='editSemester({{ $semester->id }})' :disabled="$semester->hasDependents()"
                                        color="secondary">
                                        Edit
                                    </x-button>
                                    <x-button wire:click.stop='deleteSemester({{ $semester->id }})' :disabled="$semester->hasDependents()"
                                        wire:confirm='Are you sure you want to delete this semester?' color="danger">
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
                                        'label' => 'Section',
                                        'render' => fn($data) => $data->semesterSection?->section->code ?? 'None',
                                    ],
                                    [
                                        'label' => 'Actions',
                                        'render' => 'blade:table.actions',
                                        'props' => [
                                            'actions' => [
                                                'edit' => [
                                                    'wire:click' => fn($d) => "editStudentSubject({$d->id})",
                                                ],
                                                'archive' => [
                                                    'wire:click' => fn($d) => "archiveStudentSubject({$d->id})",
                                                ],
                                                'unarchive' => [
                                                    'wire:click' => fn($d) => "unarchiveStudentSubject({$d->id})",
                                                ],
                                                'delete' => [
                                                    'wire:click' => fn($d) => "deleteStudentSubject({$d->id})",
                                                ],
                                            ],
                                        ],
                                    ],
                                ];
                            @endphp
                            <x-table :data="$semester->teacherSubjects()->lazy()" :columns="$columns">
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
        @if ($semesterFormIsOpen)
            <x-modal-scrim />
            <x-dialog.container>
                <x-dialog wire:submit="saveSemester" el="form">
                    <x-dialog.title>
                        @isset($semesterForm->id)
                            @if (!$semesterForm->include_base && $semesterForm->include_subjects)
                                Add Subjects
                            @else
                                Edit Semester
                            @endif
                        @else
                            Add Semester
                        @endisset
                    </x-dialog.title>
                    <x-dialog.content>
                        @if ($semesterForm->include_subjects)
                            <x-form-control>
                                <x-form-control.label key="semesterForm.semester_id">
                                    Semester
                                </x-form-control.label>
                                <x-combobox :options="$semesters" :value="fn($data) => $data->id" :label="fn($data) => (string) $data"
                                    wire:model.live="semesterForm.semester_id" empty="No Semesters Found"
                                    placeholder="Select a Semester" />
                                <x-form-control.error-text key="semesterForm.semester_id" />
                            </x-form-control>
                            <x-form-control>
                                <x-form-control.label key="semesterForm.course_id">
                                    Course
                                </x-form-control.label>
                                <x-combobox :options="$courses" :value="fn($data) => $data->id" :label="fn($data) => $data->name . ' (' . $data->code . ')'"
                                    wire:model.live="semesterForm.course_id" empty="No Courses Found"
                                    placeholder="Select a Course" />
                                <x-form-control.error-text key="semesterForm.course_id" />
                            </x-form-control>
                            @isset($semesterForm->course_id)
                                <x-form-control>
                                    <x-form-control.label key="semesterForm.course_subject_ids">
                                        Subjects
                                    </x-form-control.label>
                                    <x-combobox multiple :options="$courseSubjects" :value="fn($data) => $data->id" :label="fn($data) => $data->subject->name . ' (' . $data->subject->code . ')'"
                                        wire:model="semesterForm.course_subject_ids" placeholder="Select subjects" />

                                    <x-form-control.error-text key="semesterForm.course_subject_ids.*" />
                                    <x-form-control.error-text key="semesterForm.course_subject_ids" />
                                </x-form-control>
                            @endisset
                        @endif
                    </x-dialog.content>
                    <x-dialog.actions>
                        <x-button wire:click="closeSemesterForm" color="neutral" variant="text" type="button">
                            Cancel
                        </x-button>
                        <x-button wire:click="closeSemesterForm">
                            Save
                        </x-button>
                    </x-dialog.actions>
                </x-dialog>
            </x-dialog.container>
        @endif
    </x-layouts.loader>
</div>
