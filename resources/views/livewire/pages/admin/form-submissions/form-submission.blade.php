<div class="container mx-auto p-4 grow flex flex-col gap-4">
    <div class="flex flex-col gap-4 justify-center bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-bold">
            {{ $formSubmission->submissionPeriod->name }}
        </h2>
        @isset($formSubmission->submissionPeriod->semester)
            <h2 class="text-lg font-extralight">
                {{ $formSubmission->submissionPeriod->semester }}
            </h2>
        @endisset
        <div class="flex flex-row">
            <div class="flex-grow">
                <h2 class="text-sm">Evaluator ({{ $formSubmission->evaluator->role->display_name }})</h2>
                <h2 class="text-xl">{{ $formSubmission->evaluator->name }}</h2>
            </div>
            <div class="flex-grow">
                <h2 class="text-sm">Evaluatee ({{ $formSubmission->evaluatee->role->display_name }})</h2>
                <h2 class="text-xl">
                    {{ $formSubmission->evaluatee->name }}
            </div>
        </div>
        </h2>
        {{-- <h2 class="text-xl">Student: {{ $formSubmission->studentSubject->studentSemester->student->user->name }}</h2>
        <h2 class="text-xl">{{ $formSubmission->studentSubject->subject_name }}
            ({{ $formSubmission->studentSubject->subject_code }})
        </h2> --}}
    </div>
    <form wire:submit.prevent="save">
        <x-forms.form-submission-form :form="$formModel" :formSubmission="$formSubmission" readonly :createWireModel="$this->getCreateWireModel()" showValues
            showSummary />
    </form>
</div>
