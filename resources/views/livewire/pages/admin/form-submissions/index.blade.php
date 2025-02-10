<div class="contents">
    <x-sections.header title="Form Submissions" />
    <div class="main-dash grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="col-span-1 md:col-span-3 overflow-auto">
            @php
                $columns = [
                    ['label' => 'Student', 'render' => 'student.student.student_number'],
                    ['label' => 'Teacher', 'render' => 'teacher.user.name'],
                    ['label' => 'Subject', 'render' => 'student.studentSubject.subject_name'],
                    ['label' => 'Semester', 'render' => 'student.studentSubject.studentSemester.semester'],
                    ['label' => 'Rating', 'render' => 'rating'],
                    [
                        'label' => 'Actions',
                        'render' => 'blade:table.actions',
                        'props' => [
                            'mergeDefaultActions' => false,
                            'actions' => [
                                'view' => [
                                    'order' => 0,
                                    'type' => 'link',
                                    'label' => 'View',
                                    'color' => 'primary',
                                    'href' => fn($data) => route('admin.form-submissions.form-submission', [
                                        'formSubmission' => $data->id,
                                    ]),
                                ],
                            ],
                        ],
                    ],
                ];
            @endphp
            <x-table :data="$formSubmissions" :$columns :paginate="15" />
        </div>
    </div>
</div>
