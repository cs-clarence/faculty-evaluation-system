<div class="container mx-auto p-4 grow flex flex-col gap-4">
    <div class="flex flex-col gap-4 justify-center bg-white shadow-md rounded-lg p-6 mb-4 ">
        <h2 class="text-2xl font-bold">{{ $formSubmission->submissionPeriod->name }}
            ({{ $formSubmission->submissionPeriod->semester }})</h2>
        <h2 class="text-xl">Teacher: {{ $formSubmission->teacher->user->name }}</h2>
        <h2 class="text-xl">Student: {{ $formSubmission->studentSubject->studentSemester->student->user->name }}</h2>
        <h2 class="text-xl">{{ $formSubmission->studentSubject->subject_name }}
            ({{ $formSubmission->studentSubject->subject_code }})
        </h2>
    </div>
    <form wire:submit.prevent="save">
        <h2 class="text-xl">{{ $formSubmission->teacher->name }}</h2>
        <x-forms.form-submission-form :form="$formModel" :formSubmission="$formSubmission" readonly :createWireModel="$this->getCreateWireModel()" />
    </form>
</div>
