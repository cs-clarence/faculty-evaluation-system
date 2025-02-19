@php
    use App\Models\FormQuestionType as Type;
@endphp

@props([
    'form',
    'readonly' => false,
    'showFormName' => false,
    'createWireModel' => null,
    'formSubmission' => null,
    'showValues' => false,
    'showSummary' => false,
])

<div>
    @if ($showFormName)
        @isset($form->name)
            <h2 class="text-2xl font-bold text-gray-800">{{ $form->name }}</h2>
        @endisset
    @endif

    @if ($showSummary && isset($formSubmission))
        <div class="bg-white shadow-md rounded-lg p-6 mb-4 flex flex-col gap-4 border-2 border-gray-300">
            <h3 class="font-semibold text-xl">Summary</h3>
            <p>
                Total: {{ $formSubmission->getRating() }}% / 100
            </p>
            <table>
                <thead>
                    <tr>
                        <th>Question</th>
                        <th>Value</th>
                        <th>Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($formSubmission->getSummary() as $breakdown)
                        <tr>
                            <td>{{ $breakdown['question'] }}</td>
                            <td>{{ $breakdown['value'] }}</td>
                            <td>{{ $breakdown['percentage'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @forelse ($form->sections()->reordered()->get() as $section)
        <div class="bg-white shadow-md rounded-lg p-6 mb-4 flex flex-col gap-4">
            <h3 class="font-semibold text-xl">{{ $section->title }}</h3>
            @isset($section->description)
                <p class="text-gray-700">{{ $section->description }}</p>
            @endisset
            <div class="flex flex-col gap-6">
                @forelse ($section->questions()->reordered()->get() as $question)
                    @php($questionName = "form.questions.{$question->id}")
                    @php($wireModel = isset($createWireModel) ? $createWireModel($question) : $questionName)
                    <div class="flex flex-col gap-3">
                        <h4 class="font-semibold text-lg">{{ $question->title }}</h4>
                        @if ($question->type === Type::Essay->value)
                            <textarea @disabled($readonly) class="w-full px-3 py-2 border rounded-lg min-h-32" rows="4"
                                wire:model="{{ $wireModel }}" name="{{ $questionName }}"></textarea>
                        @elseif ($question->type === Type::MultipleChoicesSingleSelect->value)
                            <fieldset class="flex flex-col gap-2">
                                @forelse ($question->options->sortByDesc('value') as $option)
                                    <div class="flex items-center gap-2">
                                        @php($optionName = $question->id)
                                        @php($optionId = 'option-' . $option->id)
                                        <input type="radio" wire:model="{{ $wireModel }}" class=""
                                            wire:key='option-{{ $option->id }}' @disabled($readonly)
                                            name="{{ $questionName }}" id="{{ $optionId }}"
                                            value="{{ $option->id }}">
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
                                @forelse ($question->options()->reordered()->get()->sortByDesc('value') as $option)
                                    <div class="flex items-center gap-2">
                                        @php($optionId = 'option-' . $option->id)
                                        <input type="checkbox" wire:model="{{ $wireModel }}" class=""
                                            name="{{ $questionName }}" wire:key='option-{{ $option->id }}'
                                            @disabled($readonly) wire:model="{{ $wireModel }}"
                                            id="{{ $optionId }}" value="{{ $option->id }}">
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
                        @if ($showValues && isset($formSubmission))
                            @php($value = $formSubmission->getValue($question->id))
                            @php($interpretation = $formSubmission->getInterpretation($question->id))
                            @php($reason = $formSubmission->getReason($question->id))
                            @isset($value)
                                <p class="text-gray-500 text">
                                    Value: {{ $value }} ({{ $formSubmission->getWeightedValue($question->id) }}%)
                                </p>
                            @endisset
                            @isset($interpretation)
                                <p class="text-gray-500 text">
                                    Interpretation: {{ $interpretation }}
                                </p>
                            @endisset
                            @isset($reason)
                                <p class="text-gray-500 text">
                                    Reason: {{ $reason }}
                                </p>
                            @endisset
                        @endif
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

</div>
