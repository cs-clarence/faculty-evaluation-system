@php
    use Illuminate\Support\Number;
@endphp

<div class="contents">
    <x-layouts.loader>
        <div class="container mx-auto p-4">
            <div class="flex justify-between mb-4">
                <h1 class="text-xl font-bold">{{ $student->user->name }} - Semesters</h1>
                <x-button wire:click='openSemesterForm'>
                    Add Semester
                </x-button>
            </div>

            <x-accordion>
                @forelse ($student->studentSemesters()->orderBy('year_level')->get() as $semester)
                    <x-accordion.item :key="$semester->id">
                        <x-accordion.item-header>
                            <h2 class="text-xl" x-on:click="toggle()">
                                {{ Number::ordinal($semester->year_level) }} Year
                                - {{ $semester->semester }}
                                <span class="text-lg text-gray-400">({{ $semester->studentSubjects()->count() }}
                                    Subject(s))
                                </span>
                            </h2>
                            <div class="flex-grow"></div>
                            <div class="flex flex-row justify-end gap-1 items-center">
                                <x-button wire:click.stop='openAddSubjectsForm({{ $semester->id }})' color="primary">
                                    Add Subjects
                                </x-button>
                                <x-button wire:click.stop='editSemesterSection({{ $semester->id }})' color="secondary"
                                    variant="outlined">
                                    Edit Section
                                </x-button>

                                @if (!$semester->hasDependents())
                                    <x-button wire:click.stop='deleteSemester({{ $semester->id }})' :disabled="$semester->hasDependents()"
                                        variant="outlined" wire:confirm='Are you sure you want to delete this semester?'
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
                                        'label' => 'Section',
                                        'render' => fn($data) => $data->semesterSection?->section->code ?? 'None',
                                    ],
                                    [
                                        'label' => 'Actions',
                                        'render' => 'blade:table.actions',
                                        'props' => [
                                            'actions' => [
                                                'edit' => [
                                                    'wire:click' => fn($d) => "editSubject({$d->id})",
                                                ],
                                                'archive' => [
                                                    'wire:click' => fn($d) => "archiveSubject({$d->id})",
                                                ],
                                                'unarchive' => [
                                                    'wire:click' => fn($d) => "unarchiveSubject({$d->id})",
                                                ],
                                                'delete' => [
                                                    'wire:click' => fn($d) => "deleteSubject({$d->id})",
                                                ],
                                            ],
                                        ],
                                    ],
                                ];
                            @endphp
                            <x-table :data="$semester->studentSubjects()->lazy()" :columns="$columns">
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
            <x-dialog.container wire:click.self="closeSemesterForm">
                <x-dialog wire:submit="saveSemester" el="form" wire:key="semester-form">
                    <x-dialog.title>
                        @isset($semesterForm->id)
                            @if (!$semesterForm->include_base && !$semesterForm->include_section && $semesterForm->include_subjects)
                                Add Subjects
                            @elseif (!$semesterForm->include_base && !$semesterForm->include_subjects && $semesterForm->include_section)
                                Edit Section
                            @else
                                Edit Semester
                            @endif
                        @else
                            Add Semester
                        @endisset
                    </x-dialog.title>
                    <x-dialog.content>
                        <input type="hidden" name="semesterForm.student_id" wire:model="semesterForm.student_id"
                            value="{{ $student->id ?? null }}" />
                        <input type="hidden" name="semesterForm.id" wire:model="semesterForm.id"
                            value="{{ $semesterForm->id ?? null }}" />

                        @if (!$semesterForm->include_section)
                            <input type="hidden" name="semesterForm.course_semester_id"
                                wire:model="semesterForm.course_semester_id"
                                value="{{ $semesterForm->course_semester_id ?? null }}" />
                        @endif

                        @if ($semesterForm->include_base)
                            <x-form-control>
                                <x-form-control.label key="semesterForm.semester_id">
                                    Semester
                                </x-form-control.label>
                                <x-combobox :options="$semesters" :value="fn($data) => $data->id" :label="fn($data) => (string) $data"
                                    wire:model.live="semesterForm.semester_id" key="semesterForm.semester_id"
                                    empty="No Semesters Found" placeholder="Select a Semester" />
                                <x-form-control.error-text key="semesterForm.semester_id" />
                            </x-form-control>
                            <x-form-control>
                                <x-form-control.label key="semesterForm.year_level">
                                    Year Level
                                </x-form-control.label>
                                <x-input key="semesterForm.year_level" wire:model.live="semesterForm.year_level"
                                    type="number" placeholder="Year Level" />
                                <x-form-control.error-text key="semesterForm.year_level" />
                            </x-form-control>
                            <x-form-control>
                                <x-form-control.label key="semesterForm.course_semester_id">
                                    Reference Course Semester
                                </x-form-control.label>
                                <x-combobox :options="$courseSemesters" :value="fn($data) => $data->id" :label="fn($data) => (string) $data"
                                    wire:model.live="semesterForm.course_semester_id"
                                    key="semesterForm.course_semester_id" empty="No Semesters Found"
                                    placeholder="Select a Semester" />
                                <x-form-control.error-text key="semesterForm.course_semester_id" />
                            </x-form-control>
                        @endif

                        @if ($semesterForm->include_section && isset($semesterForm->semester_id) && isset($semesterForm->year_level))
                            <x-form-control>
                                <x-form-control.label key="semesterForm.section_id">
                                    Section
                                </x-form-control.label>
                                <x-combobox :options="$sections" :value="fn($data) => $data->id" :label="fn($data) => $data->code"
                                    key="semesterForm.section_id"
                                    wire:key="semesterForm.section_id-{{ rand(0, 100) }}"
                                    wire:model="semesterForm.section_id" empty="No Sections Found"
                                    placeholder="Select Section" />
                                <x-form-control.error-text key="semesterForm.section_id" />
                            </x-form-control>
                            <x-form-control>
                                <div class="flex flex-row gap-2 items-center">
                                    <input id="semesterForm.same_section_for_subjects" type="checkbox"
                                        name="semesterForm.same_section_for_subjects"
                                        wire:model="semesterForm.same_section_for_subjects" />
                                    <x-form-control.label key="semesterForm.same_section_for_subjects">
                                        Assign Section To Subjects
                                    </x-form-control.label>
                                </div>
                                <x-form-control.error-text key="semesterForm.same_section_for_subjects" />
                            </x-form-control>
                        @endif

                        @if ($semesterForm->include_subjects)
                            <x-form-control>
                                <x-form-control.label key="semesterForm.course_subject_ids">
                                    Subjects
                                </x-form-control.label>
                                <x-combobox multiple :options="$courseSubjects" :value="fn($data) => $data->id" :label="fn($data) => $data->subject->name . ' (' . $data->subject->code . ')'"
                                    key="semesterForm.course_subject_ids" wire:model="semesterForm.course_subject_ids"
                                    placeholder="Select subjects" empty="No subjects found" />
                                <x-form-control.error-text key="semesterForm.course_subject_ids" />
                            </x-form-control>
                        @endif
                    </x-dialog.content>
                    <x-dialog.actions>
                        <x-button wire:click="closeSemesterForm" color="neutral" variant="text" type="button">
                            Cancel
                        </x-button>
                        <x-button type="submit">
                            Save
                        </x-button>
                    </x-dialog.actions>
                </x-dialog>
            </x-dialog.container>
        @endif
        @if ($subjectFormIsOpen)
            <x-modal-scrim />
            <x-dialog.container wire:click.self="closeSubjectForm">
                <x-dialog wire:submit="saveSubject" el="form" wire:key="subject-form">
                    <x-dialog.title>
                        @isset($subjectForm->id)
                            Edit Subject
                        @else
                            Add Subject
                        @endisset
                    </x-dialog.title>
                    <x-dialog.content>
                        <input type="hidden" name="subjectForm.course_subject_id"
                            wire:model="subjectForm.course_subject_id"
                            value="{{ $subjectForm->course_subject_id ?? null }}" />
                        <input type="hidden" name="subjectForm.id" wire:model="subjectForm.id"
                            value="{{ $subjectForm->id ?? null }}" />
                        <input type="hidden" name="subjectForm.student_semester_id"
                            wire:model="subjectForm.student_semester_id"
                            value="{{ $subjectForm->student_semester_id ?? null }}" />

                        <x-form-control>
                            <x-form-control.label key="subject">
                                Subject
                            </x-form-control.label>
                            <x-input key="subject" disabled :value="$subject->subject" />
                        </x-form-control>
                        <x-form-control>
                            <x-form-control.label key="subjectForm.section_id">
                                Section
                            </x-form-control.label>
                            <x-combobox :options="$sections" :value="fn($data) => $data->id" :label="fn($data) => $data->code"
                                wire:model="subjectForm.section_id" empty="No Sections Found"
                                placeholder="Select Section" />
                            <x-form-control.error-text key="subjectForm.section_id" />
                        </x-form-control>
                    </x-dialog.content>
                    <x-dialog.actions>
                        <x-button wire:click="closeSubjectForm" color="neutral" variant="text" type="button">
                            Cancel
                        </x-button>
                        <x-button type="submit">
                            Save
                        </x-button>
                    </x-dialog.actions>
                </x-dialog>
            </x-dialog.container>
        @endif
    </x-layouts.loader>
</div>
