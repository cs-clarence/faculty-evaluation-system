<div class="contents">
    <x-layouts.loader>
        <form wire:submit.prevent="save" class="container mx-auto p-4 grow flex flex-col gap-4">
            <div class="flex flex-col gap-4 justify-center bg-white shadow-md rounded-lg p-6 mb-4 ">
                <h2 class="text-2xl">{{ $formSubmissionPeriod->name }}
                </h2>
                @isset($formSubmissionPeriod->semester)
                    <h3 class="text-lg text-gray-800">
                        {{ $formSubmissionPeriod->semester }}
                    </h3>
                @endisset
                @isset($courseSubject)
                    <h2 class="text-xl">{{ $courseSubject->course->name }}</h2>
                    <h2 class="text-xl">{{ $courseSubject->subject->name }}</h2>
                @endisset
                @isset($evaluatee)
                    <h2 class="text-xl">{{ $evaluatee->name }}</h2>
                    <input type="hidden" name="form.evaluatee_id" value="{{ $evaluatee->id }}"
                        wire:model="form.evaluatee_id" />
                @else
                    <div class="mb-4 max-w-sm">
                        @php($evaluateeRole = $formSubmissionPeriod->evaluateeRole->display_name)

                        <x-form-control>
                            <x-form-control.label key="form.evaluatee_id">
                                {{ $evaluateeRole }}
                            </x-form-control.label>
                            <x-select key="form.evaluatee_id" wire:model="form.evaluatee_id"
                                empty="No {{ $evaluateeRole }} found" placeholder="Select {{ $evaluateeRole }}"
                                :options="$users" value="id" label="name" />
                            <x-form-control.error-text key="form.evaluatee_id" />
                        </x-form-control>
                    </div>
                @endisset
            </div>
            <x-forms.form-submission-form :form="$formModel" :readonly="false" :createWireModel="$this->getCreateWireModel()" />
            @error('*')
                <x-error-text>Some field have errors. Please check</x-error-text>
                @foreach ($errors->all() as $error)
                    <x-error-text>{{ $error }}</x-error-text>
                @endforeach
            @enderror
            <div class="flex justify-end mb-16">
                <button type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Submit</button>
            </div>
        </form>
    </x-layouts.loader>
</div>
