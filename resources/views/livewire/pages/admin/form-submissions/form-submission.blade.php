@php
    use App\Models\FormSubmission;
@endphp
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
            @can('viewEvaluator', FormSubmission::class)
                <div class="flex-grow">
                    <h2 class="text-sm">Evaluator ({{ $formSubmission->evaluator->role->display_name }})</h2>
                    <h2 class="text-xl">{{ $formSubmission->evaluator->name }}</h2>
                </div>
            @endcan
            <div class="flex-grow">
                <h2 class="text-sm">Evaluatee ({{ $formSubmission->evaluatee->role->display_name }})</h2>
                <h2 class="text-xl">
                    {{ $formSubmission->evaluatee->name }}
            </div>
            @isset($formSubmission->subject)
                <div class="flex-grow">
                    <h2 class="text-sm">Subject</h2>
                    <h2 class="text-xl">
                        {{ $formSubmission->subject->name }}
                </div>
            @endisset
        </div>
        </h2>
        {{-- <h2 class="text-xl">Student: {{ $formSubmission->studentSubject->studentSemester->student->user->name }}</h2>
        <h2 class="text-xl">{{ $formSubmission->studentSubject->subject_name }}
            ({{ $formSubmission->studentSubject->subject_code }})
        </h2> --}}
    </div>

    <div class="bg-white shadow-md rounded-lg p-6 flex flex-col gap-6 border-2 border-gray-300">
        <div class="flex flex-row gap-3 items-center">
            <h3 class="font-semibold text-xl">Summary</h3>
            <div class="flex flex-row gap-1 items-center">
                <x-button variant="outlined" size="sm" wire:click="exportPdf">
                    <x-icon>picture_as_pdf</x-icon>
                    Export PDF
                </x-button>
                <x-button variant="outlined" size="sm" wire:click="exportExcel">
                    <x-icon>data_table</x-icon>
                    Export Excel
                </x-button>
                <x-button variant="outlined" size="sm" wire:click="exportCsv">
                    <x-icon>data_table</x-icon>
                    Export CSV
                </x-button>
            </div>
        </div>
        <x-form-submission-summary.table :$formSubmission :createQuestionLink="fn($data) => '#q.' . $data->id" />
    </div>
    <x-forms.form-submission-form :form="$formModel" :formSubmission="$formSubmission" readonly :createWireModel="$this->getCreateWireModel()" showValues
        :createQuestionId="fn($data) => 'q.' . $data->id" showSummary />
</div>
