<div class="container mx-auto p-4 flex-grow flex flex-col">
    <h1 class="text-2xl font-bold mb-6">Pending Evaluations</h1>

    <!-- Evaluation Forms List -->
    @forelse ($submissionPeriods as $submissionPeriod)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($submissionPeriod->studentSubjects as $subject)
                <a href="{{ route('student.form-submission.index', [
                    'studentSubject' => $subject->id,
                    'formSubmissionPeriod' => $submissionPeriod->formSubmissionPeriod->id,
                    'teacher' => $subject->teacherSubject?->teacherSemester->teacher->id,
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
    @empty
        <div class="flex-grow flex flex-col justify-center items-center h-full">
            <div class="flex flex-col items-center justify-center bg-white shadow-md rounded-lg p-6">
                <h1 class="text-2xl mb-2">No Pending Evaluations</h1>
                <p class="text-gray-500 text">
                    You have no pending evaluations. Please check back later.
                </p>
            </div>
        </div>
    @endforelse
</div>
