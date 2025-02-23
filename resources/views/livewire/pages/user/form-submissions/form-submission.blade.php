<div class="container mx-auto p-4 grow flex flex-col gap-4">
    <div class="flex flex-col gap-4 justify-center bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-bold">{{ $formSubmission->submissionPeriod->name }}
            ({{ $formSubmission->submissionPeriod->semester }})</h2>
        <h2 class="text-xl">Teacher: {{ $formSubmission->evaluatee->name }}</h2>
    </div>
    <form wire:submit.prevent="save">
        <x-forms.form-submission-form :form="$formModel" :formSubmission="$formSubmission" readonly :createWireModel="$this->getCreateWireModel()" showValues />
    </form>
</div>
