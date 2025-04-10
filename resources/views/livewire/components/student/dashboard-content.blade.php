<div class="container mx-auto p-4 grow flex flex-col gap-6">
    <x-sections.header title="Pending Evaluations" />

    <!-- Evaluation Forms List -->
    @forelse ($submissionPeriods as $submissionPeriod)
        <div class="flex flex-col gap-4">
            <h2 class="text-xl font-bold text-gray-800">{{ $submissionPeriod->formSubmissionPeriod->name }}
                ({{ $submissionPeriod->formSubmissionPeriod->semester }})
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($submissionPeriod->studentSubjects as $subject)
                    <a href="{{ route('user.submit-form.index', [
                        'formSubmissionPeriod' => $submissionPeriod->formSubmissionPeriod->id,
                        'evaluatee' => $subject->teacherSubject?->teacherSemester->teacher->user->id,
                        'courseSubjectId' => $subject->course_subject_id,
                        'studentSubjectId' => $subject->id,
                    ]) }}"
                        wire:navigate wire:key="pending-evaluation-{{ $subject->id }}"
                        class="block bg-white shadow-md rounded-lg p-6 hover:shadow-lg hover:bg-blue-50 transition duration-200">
                        <h2 class="text-xl font-semibold mb-2">{{ $subject->subject_name }}
                            ({{ $subject->subject_code }})
                        </h2>
                        <p class="text-gray-900 font-medium text-lg mb-2">Teacher:
                            {{ $subject->teacherSubject?->teacherSemester?->teacher?->name ?? 'None specified' }}
                        </p>
                        <p class="text-gray-800 text-md mb-2">
                            {{ $subject->course_name }} ({{ $subject->course_code }})
                        </p>
                        <p class="text-gray-500 text-sm">
                            {{ $subject->department_name }} ({{ $subject->department_code }})
                        </p>
                    </a>
                @endforeach
            </div>
        </div>
    @empty
        <div class="grow flex flex-col justify-center items-center h-full">
            <div class="flex flex-col items-center justify-center bg-white shadow-md rounded-lg p-6">
                <h1 class="text-2xl mb-2">No Pending Evaluations</h1>
                <p class="text-gray-500 text">
                    You have no pending evaluations. Please check back later.
                </p>
            </div>
        </div>
    @endforelse
</div>
