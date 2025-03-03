@php
    use Illuminate\Support\Number;
@endphp


<div class="contents">
    <x-layouts.loader>
        <div class="container mx-auto p-4">
            <div class="flex justify-between mb-4">
                <h1 class="text-xl font-bold">{{ $student->user->name }} - Semesters</h1>
                <x-button wire:click='openCourseSemesterForm'>
                    Add Semester
                </x-button>
            </div>

            <x-accordion>
                @forelse ($student->studentSemesters as $semester)
                    <x-accordion.item :key="$semester->id">
                        <x-accordion.item-header>
                            <h2 class="text-xl" x-on:click="toggle()">
                                {{ Number::ordinal($semester->year_level) }} Year -
                                {{ $semester->semester }}
                                <span class="text-lg text-gray-400">({{ $semester->studentSubjects()->count() }}
                                    Subject(s))
                                </span>
                            </h2>
                            <div class="flex-grow"></div>
                            <div class="flex flex-row justify-end gap-1 items-center">
                                <x-button wire:click.stop='openAddStudentSubjectsForm({{ $semester->id }})'
                                    color="primary">
                                    Add Subjects
                                </x-button>

                                @if (!$semester->hasDependents())
                                    <x-button wire:click.stop='editStudentSemester({{ $semester->id }})'
                                        :disabled="$semester->hasDependents()" color="secondary">
                                        Edit
                                    </x-button>
                                    <x-button wire:click.stop='deleteStudentSemester({{ $semester->id }})'
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
    </x-layouts.loader>
</div>
