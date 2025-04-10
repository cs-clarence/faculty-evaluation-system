@php
    use App\Models\FormQuestionType as Type;

    if (!function_exists('orderedKey')) {
        function orderedKey($model)
        {
            return "{$model->id}.{$model->order}";
        }
    }

    if (!function_exists('pascalToTitle')) {
        function pascalToTitle($pascal)
        {
            return trim(ucwords(preg_replace('/([a-z])([A-Z])/', '$1 $2', $pascal)));
        }
    }

    $questionTypes = Type::cases();
    $questionTypeOptions = collect($questionTypes)
        ->filter(fn($d) => $d !== Type::MultipleChoicesMultipleSelect)
        ->map(fn($d) => ['label' => pascalToTitle($d->name), 'value' => $d->value])
        ->toArray();
    $editable = !$form->hasDependents();
@endphp

<div>
    <x-layouts.loader>
        <x-sections.header :title="$form->name">
            @if ($editable)
                <x-button wire:click='openSectionForm'>
                    <i class="material-symbols-outlined">add</i>
                    Add Section
                </x-button>
            @endif
        </x-sections.header>

        <p class="text-xl text-gray-800 mb-4">
            {{ isset($form->description) && $form->description !== '' ? $form->description : 'No description provided' }}
        </p>

        @if (!$editable)
            <x-error-text class="mb-4 block">
                Since this form is used in a 'submission period', it is now uneditable.
            </x-error-text>
        @endif
        @php($sections = $form->sections()->reordered()->get())

        @forelse ($sections as $key => $section)
            <div class="bg-white shadow-md rounded-lg p-6 mb-4 flex flex-col gap-4" wire:key="{{ orderedKey($section) }}">
                <div class="flex flex-row gap-2 w-full items-center">
                    <x-editable-text required :text="$section->title" placeholder="Section title" el="h3"
                        wire:save="updateSectionTitle({{ $section->id }}, $event.detail.text)"
                        class="font-semibold text-xl flex-grow" :$editable>
                        <x-slot:input class="w-full"></x-slot:input>
                    </x-editable-text>
                    @if ($editable)
                        @isset($sections[$key - 1])
                            <x-button icon size="sm" color="secondary"
                                wire:click="moveBeforeSection({{ $section->id }}, {{ $sections[$key - 1]->id }})">
                                <i class="material-symbols-outlined">arrow_upward</i>
                            </x-button>
                        @endisset
                        @isset($sections[$key + 1])
                            <x-button icon size="sm" color="secondary"
                                wire:click="moveAfterSection({{ $section->id }}, {{ $sections[$key + 1]->id }})">
                                <i class="material-symbols-outlined">arrow_downward</i>
                            </x-button>
                        @endisset
                        <x-button icon size="sm" color="danger"
                            wire:confirm="Are you sure you want to delete this section?"
                            wire:click="deleteSection({{ $section->id }})">
                            <i class="material-symbols-outlined">delete</i>
                        </x-button>
                    @endif
                </div>
                <x-editable-text :multiline="true" :text="$section->description" empty="No description" :$editable
                    placeholder="Section description"
                    wire:save="updateSectionDescription({{ $section->id }}, $event.detail.text)"
                    class="text-md white whitespace-pre-line" />
                <div class="flex flex-row gap-2">
                    @if ($editable)
                        <x-button size="sm" variant="outlined" wire:click='openQuestionForm({{ $section->id }})'>
                            <i class="material-symbols-outlined">add</i>
                            Add Question</x-button>
                    @endif
                </div>

                <div class="flex flex-col gap-6">
                    @php($questions = $section->questions()->reordered()->get())
                    @forelse ($questions as $key => $question)
                        @php($questionName = "form.questions.{$question->id}")
                        @php($wireModel = isset($createWireModel) ? $createWireModel($question) : $questionName)
                        <div class="flex flex-col gap-3 p-4 border border-gray-300 rounded-lg"
                            wire:key="{{ orderedKey($question) }}">
                            <div class="flex flex-row gap-2 w-full items-center">
                                <x-editable-text el="h4" class="font-semibold text-lg w-full" :text="$question->title"
                                    :$editable required
                                    wire:save="updateQuestionTitle({{ $question->id }}, $event.detail.text)">
                                    <x-slot:input class="w-full">
                                    </x-slot:input>
                                </x-editable-text>

                                @if ($editable)
                                    @isset($questions[$key - 1])
                                        <x-button icon size="sm" color="secondary"
                                            wire:click="moveBeforeQuestion({{ $question->id }}, {{ $questions[$key - 1]->id }})">
                                            <i class="material-symbols-outlined">arrow_upward</i>
                                        </x-button>
                                    @endisset
                                    @isset($questions[$key + 1])
                                        <x-button icon size="sm" color="secondary"
                                            wire:click="moveAfterQuestion({{ $question->id }}, {{ $questions[$key + 1]->id }})">
                                            <i class="material-symbols-outlined">arrow_downward</i>
                                        </x-button>
                                    @endisset
                                    <x-button icon size="sm" color="danger"
                                        wire:confirm="Are you sure you want to delete this question?"
                                        wire:click="deleteQuestion({{ $question->id }})">
                                        <i class="material-symbols-outlined">delete</i>
                                    </x-button>
                                @endif
                            </div>

                            <div class="max-w-max items-center flex-row flex gap-2" x-data="{ questionType: '{{ addslashes($question->type) }}' }">
                                <x-label class="text-lg min-w-max" key="{{ $question->id }}.type">Question
                                    Type</x-label>
                                <x-select class="min-w-[280px]" x-model="questionType" key="{{ $question->id }}.type"
                                    wire:change="updateQuestionType({{ $question->id }}, $event.target.value)"
                                    :options="$questionTypeOptions" empty="No types available" :disabled="!$editable" />
                                @if ($editable)
                                    @if (
                                        $question->type === Type::MultipleChoicesMultipleSelect->value ||
                                            $question->type === Type::MultipleChoicesSingleSelect->value)
                                        <x-button size="sm" variant="outlined" class="min-w-max"
                                            wire:click='openOptionForm({{ $question->id }})'>
                                            <i class="material-symbols-outlined">add</i>
                                            Add Option</x-button>
                                    @endif
                                    <x-button size="sm" variant="outlined" color="secondary"
                                        wire:click='openQuestionForm({{ $section->id }}, {{ $question->id }})'>
                                        <i class="material-symbols-outlined">edit</i>
                                        Edit Question
                                    </x-button>
                                @endif
                            </div>

                            <p class="min-w-max text-gray-600">Weight: {{ $question->weight }}</p>
                            @if ($question->type === Type::Essay->value)
                                {{-- <textarea class="w-full px-3 py-2 border rounded-lg min-h-32" rows="4" wire:model="{{ $wireModel }}"
                                name="{{ $questionName }}"></textarea> --}}
                                <div
                                    class="flex flex-row gap-1 align-items text-gray-700 bg-gray-100/50 w-full rounded-lg p-4">
                                    <h4>Value Range: {{ $question->essayTypeConfiguration?->value_scale_from ?? 0 }} -
                                        {{ $question->essayTypeConfiguration?->value_scale_to ?? 0 }}</h4>
                                </div>
                                @if (!isset($question->essayTypeConfiguration))
                                    <p class="text-sm text-red-400">
                                        Please edit the question and the value range.
                                    </p>
                                @endif
                            @elseif (
                                $question->type === Type::MultipleChoicesMultipleSelect->value ||
                                    $question->type === Type::MultipleChoicesSingleSelect->value)
                                <div class="flex flex-col gap-2">
                                    @php($options = $question->options()->reordered()->orderByDesc('value')->get())
                                    @forelse ($options as $key => $option)
                                        <div class="flex flex-row gap-1 align-items text-gray-700 bg-gray-100/50 w-full rounded-lg p-4"
                                            wire:key="{{ orderedKey($option) }}">
                                            <div class="flex items-center gap-2 flex-grow">
                                                @php($optionId = 'option-' . $option->id)
                                                <div for="{{ $optionId }}" class="block text font-medium ">
                                                    <h4>
                                                        Label: {{ $option->label }}
                                                    </h4>
                                                    <h4>
                                                        Interpretation: {{ $option->interpretation }}
                                                    </h4>
                                                    <h4>
                                                        Value: {{ $option->value }}
                                                    </h4>
                                                </div>
                                            </div>
                                            @if ($editable)
                                                <div class="flex flex-col gap-1">
                                                    <x-button icon size="sm" variant="outlined" color="secondary"
                                                        wire:click="openOptionForm({{ $question->id }}, {{ $option->id }})">
                                                        <i class="material-symbols-outlined">edit</i>
                                                    </x-button>
                                                    <x-button icon size="sm" variant="outlined" color="danger"
                                                        wire:confirm="Are you sure you want to delete this option?"
                                                        wire:click="deleteOption({{ $option->id }})">
                                                        <i class="material-symbols-outlined">delete</i>
                                                    </x-button>
                                                </div>
                                                <div class="flex flex-col gap-1">
                                                    @isset($options[$key - 1])
                                                        <x-button icon size="sm" color="secondary" variant="text"
                                                            wire:click="moveBeforeOption({{ $option->id }}, {{ $options[$key - 1]->id }})">
                                                            <i class="material-symbols-outlined">arrow_upward</i>
                                                        </x-button>
                                                    @endisset
                                                    @isset($options[$key + 1])
                                                        <x-button icon size="sm" color="secondary" variant="text"
                                                            wire:click="moveAfterOption({{ $option->id }}, {{ $options[$key + 1]->id }})">
                                                            <i class="material-symbols-outlined">arrow_downward</i>
                                                        </x-button>
                                                    @endisset
                                                </div>
                                            @endif
                                        </div>
                                    @empty
                                        <p class="text-sm text-red-400">
                                            No options found.
                                        </p>
                                    @endforelse
                                </div>
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
        <div class="content-center justify-items-center block mb-10">
            @if ($editable)
                <x-button size="lg" icon wire:click='openSectionForm'>
                    <i class="material-symbols-outlined">add</i>
                </x-button>
            @endif
        </div>

        @if ($sectionFormIsOpen)
            <x-modal-scrim />
            <x-dialog.container wire:click.self='closeSectionForm'>
                <x-dialog wire:key="section-form">
                    <x-dialog.title>
                        @isset($sectionForm->id)
                            Edit Section
                        @else
                            New Section
                        @endisset
                    </x-dialog.title>
                    <x-dialog.content el="form">
                        <x-input type="hidden" key="sectionForm.form_id" wire:model="sectionForm.form_id" />
                        <x-form-control>
                            <x-form-control.label key="sectionForm.title">Title</x-form-control.label>
                            <x-input key="sectionForm.title" wire:model="sectionForm.title" :disabled="!$editable" />
                            <x-form-control.error-text key="sectionForm.title" />
                        </x-form-control>
                        <x-form-control>
                            <x-form-control.label key="sectionForm.description">Description</x-form-control.label>
                            <x-input key="sectionForm.description" wire:model="sectionForm.description"
                                :disabled="!$editable" />
                            <x-form-control.error-text key="sectionForm.description" />
                        </x-form-control>
                    </x-dialog.content>
                    <x-dialog.actions>
                        <x-button variant="text" color="neutral" wire:click='closeSectionForm'>Cancel</x-button>
                        <x-button wire:click='saveSection' :disabled="!$editable">Save</x-button>
                    </x-dialog.actions>
                </x-dialog>
            </x-dialog.container>
        @endif

        @if ($questionFormIsOpen)
            <x-modal-scrim />
            <x-dialog.container wire:click.self='closeQuestionForm'>
                <x-dialog wire:key="question-form">
                    <x-dialog.title>
                        @isset($questionForm->id)
                            Edit Question
                        @else
                            New Question
                        @endisset
                    </x-dialog.title>
                    <x-dialog.content el="form">
                        <x-input type="hidden" key="questionForm.form_id" wire:model="questionForm.form_id" />
                        <x-input type="hidden" key="questionForm.form_section_id"
                            wire:model="questionForm.form_section_id" />
                        <x-form-control>
                            <x-form-control.label key="questionForm.title">Title</x-form-control.label>
                            <x-input key="questionForm.title" wire:model="questionForm.title" :disabled="!$editable" />
                            <x-form-control.error-text key="questionForm.title" />
                        </x-form-control>
                        <x-form-control>
                            <x-form-control.label key="questionForm.type">Type</x-form-control.label>
                            <x-select key="questionForm.type" wire:model.live="questionForm.type" :options="$questionTypeOptions"
                                placeholder="Select type" empty="No types available" :disabled="!$editable" />
                            <x-form-control.error-text key="questionForm.type" />
                        </x-form-control>
                        <x-form-control>
                            <x-form-control.label key="questionForm.weight">Weight</x-form-control.label>
                            <x-input key="questionForm.weight" wire:model="questionForm.weight" type="number"
                                :disabled="!$editable" />
                            <x-form-control.error-text key="questionForm.type" />
                        </x-form-control>
                        @if ($questionForm->type === Type::Essay->value)
                            <x-form-control>
                                <x-form-control.label key="questionForm.essay_value_scale_from">Value Scale
                                    Min</x-form-control.label>
                                <x-input type="number" key="questionForm.essay_value_scale_from"
                                    wire:model="questionForm.essay_value_scale_from" :disabled="!$editable" />
                                <x-form-control.error-text key="questionForm.essay_value_scale_from" />
                            </x-form-control>
                            <x-form-control>
                                <x-form-control.label key="questionForm.essay_value_scale_to">Value Scale
                                    Max</x-form-control.label>
                                <x-input type="number" key="questionForm.essay_value_scale_to"
                                    wire:model="questionForm.essay_value_scale_to" :disabled="!$editable" />
                                <x-form-control.error-text key="questionForm.essay_value_scale_to" />
                            </x-form-control>
                        @endif
                        <x-form-control>
                            <x-form-control.label key="questionForm.description">Description</x-form-control.label>
                            <x-textarea key="questionForm.description" wire:model="questionForm.description"
                                :disabled="!$editable" />
                            <x-form-control.error-text key="questionForm.description" />
                        </x-form-control>
                    </x-dialog.content>
                    <x-dialog.actions>
                        <x-button variant="text" color="neutral" wire:click='closeQuestionForm'>Cancel</x-button>
                        <x-button wire:click='saveQuestion' :disabled="!$editable">Save</x-button>
                    </x-dialog.actions>
                </x-dialog>
            </x-dialog.container>
        @endif

        @if ($optionFormIsOpen)
            <x-modal-scrim />
            <x-dialog.container wire:click.self='closeOptionForm'>
                <x-dialog wire:key="option-form">
                    <x-dialog.title>New Option</x-dialog.title>
                    <x-dialog.content el="form">
                        <x-input type="hidden" key="optionForm.form_question_id"
                            wire:model="optionForm.form_question_id" />
                        <x-form-control>
                            <x-form-control.label key="optionForm.label">Label</x-form-control.label>
                            <x-input key="optionForm.label" wire:model="optionForm.label" :disabled="!$editable" />
                            <x-form-control.error-text key="optionForm.label" />
                        </x-form-control>
                        <x-form-control>
                            <x-form-control.label key="optionForm.value">Value</x-form-control.label>
                            <x-input type="number" key="optionForm.value" wire:model="optionForm.value"
                                :disabled="!$editable" />
                            <x-form-control.error-text key="optionForm.value" />
                        </x-form-control>
                        <x-form-control>
                            <x-form-control.label key="optionForm.interpretation">Interpretation</x-form-control.label>
                            <x-textarea key="optionForm.interpretation" wire:model="optionForm.interpretation"
                                :disabled="!$editable" />
                            <x-form-control.error-text key="optionForm.interpretation" />
                        </x-form-control>
                    </x-dialog.content>
                    <x-dialog.actions>
                        <x-button variant="text" color="neutral" wire:click='closeOptionForm'>Cancel</x-button>
                        <x-button wire:click='saveOption' :disabled="!$editable">Save</x-button>
                    </x-dialog.actions>
                </x-dialog>
            </x-dialog.container>
        @endif
    </x-layouts.loader>
</div>
