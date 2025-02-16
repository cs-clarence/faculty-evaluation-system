@php
    use App\Models\FormQuestionType as Type;
@endphp

<div>
    <x-sections.header :title="$form->name">
        <x-button wire:click='openSectionForm'>
            Add Section
        </x-button>
    </x-sections.header>

    <p class="text-xl text-gray-800 mb-8">
        {{ isset($form->description) && $form->description !== '' ? $form->description : 'No description provided' }}
    </p>

    @forelse ($form->sections()->reordered()->get() as $section)
        <div class="bg-white shadow-md rounded-lg p-6 mb-4 flex flex-col gap-4" wire:key="{{ $section->id }}">
            <x-editable-text required :text="$section->title" placeholder="Section title" el="h3"
                wire:save="updateSectionTitle({{ $section->id }}, $event.detail.text)" class="font-semibold text-xl" />
            <x-editable-text :multiline="true" :text="$section->description" empty="No description" placeholder="Section description"
                wire:save="updateSectionDescription({{ $section->id }}, $event.detail.text)"
                class="font-semibold text-xl" class="w-full" />

            <div class="flex flex-col gap-6">
                @forelse ($section->questions()->reordered()->get() as $question)
                    @php($questionName = "form.questions.{$question->id}")
                    @php($wireModel = isset($createWireModel) ? $createWireModel($question) : $questionName)
                    <div class="flex flex-col gap-3">
                        <h4 class="font-semibold text-lg">{{ $question->title }}</h4>
                        @if ($question->type === Type::Essay->value)
                            <textarea class="w-full px-3 py-2 border rounded-lg min-h-32" rows="4" wire:model="{{ $wireModel }}"
                                name="{{ $questionName }}"></textarea>
                        @elseif ($question->type === Type::MultipleChoicesSingleSelect->value)
                            <fieldset class="flex flex-col gap-2">
                                @forelse ($question->options()->reordered()->orderByDesc('value')->get() as $option)
                                    <div class="flex items-center gap-2">
                                        @php($optionName = $question->id)
                                        @php($optionId = 'option-' . $option->id)
                                        <input type="radio" wire:model="{{ $wireModel }}" class=""
                                            wire:key='option-{{ $option->id }}' name="{{ $questionName }}"
                                            id="{{ $optionId }}" value="{{ $option->id }}">
                                        <label for="{{ $optionId }}" class="block text font-medium text-gray-700">
                                            {{ $option->label }}
                                        </label>
                                    </div>
                                @empty
                                    <p class="text-gray-500 text">
                                        No options found
                                    </p>
                                @endforelse
                            </fieldset>
                        @elseif ($question->type === Type::MultipleChoicesMultipleSelect->value)
                            <fieldset class="flex flex-col gap-2">
                                @forelse ($question->options()->reordered()->orderByDesc('value')->get() as $option)
                                    <div class="flex items-center gap-2">
                                        @php($optionId = 'option-' . $option->id)
                                        <input type="checkbox" wire:model="{{ $wireModel }}" class=""
                                            name="{{ $questionName }}" wire:key='option-{{ $option->id }}'
                                            wire:model="{{ $wireModel }}" id="{{ $optionId }}"
                                            value="{{ $option->id }}">
                                        >
                                        <label for="{{ $optionId }}" class="block text font-medium text-gray-700">
                                            {{ $option->label }}
                                        </label>
                                    </div>
                                @empty
                                    <p class="text-gray-500 text">
                                        No options found
                                    </p>
                                @endforelse
                            </fieldset>
                        @endif
                        @isset($formSubmission)
                            @php($value = $formSubmission->getValue($question->id))
                            @isset($value)
                                <p class="text-gray-500 text">
                                    Value: {{ $value }}
                                </p>
                            @endisset
                        @endisset
                        @error($questionName)
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                @empty
                    <p class="text-gray-500 text">
                        No questions found
                    </p>
                @endforelse
            </div>
        </div>
    @empty
        <p class="text-gray-500 text">
            No sections found
        </p>
    @endforelse

    @if ($sectionFormIsOpen)
        <x-modal-scrim />
        <x-dialog.container wire:click.self='closeSectionForm'>
            <x-dialog>
                <x-dialog.title>New Section</x-dialog.title>
                <x-dialog.content el="form">
                    <x-input type="hidden" value="{{ $form->id }}" key="sectionForm.form_id"
                        wire:model="sectionForm.form_id" />
                    <x-form-control>
                        <x-form-control.label key="sectionForm.title">Name</x-form-control.label>
                        <x-input key="sectionForm.title" wire:model="sectionForm.title" />
                        <x-form-control.error-text key="sectionForm.title" />
                    </x-form-control>
                    <x-form-control>
                        <x-form-control.label key="sectionForm.description">Description</x-form-control.label>
                        <x-input key="sectionForm.description" wire:model="sectionForm.description" />
                        <x-form-control.error-text key="sectionForm.description" />
                    </x-form-control>
                </x-dialog.content>
                <x-dialog.actions>
                    <x-button variant="text" color="neutral" wire:click='closeSectionForm'>Cancel</x-button>
                    <x-button wire:click='saveSection'>Save</x-button>
                </x-dialog.actions>
            </x-dialog>
        </x-dialog.container>
    @endif
</div>
