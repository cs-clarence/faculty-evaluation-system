@props(['multiple' => false, 'accept' => ''])

<!-- resources/views/components/file-drop.blade.php -->
<div x-data="{
    files: [],
    isFileDragging: false,
    handleDrop(event) {
        event.preventDefault();
        this.files = [...event.dataTransfer.files];
    }
}" x-on:dragover.prevent x-on:dragenter="isFileDragging = true"
    x-on:dragleave="isFileDragging = false" x-on:dragend="isFileDragging = false" x-on:drop="handleDrop($event)"
    {{ $attributes->merge(['class' => 'border-2 border-dashed border-gray-300 rounded-lg p-6 flex flex-col items-center justify-center cursor-pointer gap-2']) }}>
    <div class="contents" x-show="!isFileDragging">
        @isset($input)
            <input x-on:change="files = [...$event.target.files]"
                {{ $input->attributes->merge(['type' => 'file', 'multiple' => $multiple, 'class' => 'hidden', 'accept' => $accept, 'x-ref' => 'fileInput']) }}
                x-on:clear-inputs.window="files = []; $el.value = ''">
        @else
            <input type="file" multiple="{{ $multiple }}" class="hidden" x-ref="fileInput"
                accept="{{ $accept }}" x-on:change="files = [...$event.target.files]"
                x-on:clear-inputs.window="files = []; $el.value = ''">
        @endisset
        <p class="text-gray-600">Drag and drop files here or</p>
        <x-button type="button" variant="outlined" size="sm"
            x-on:click="$refs.{{ isset($input) ? ($input->attributes->has('x-ref') ? $input->attributes->get('x-ref') : 'fileInput') : 'fileInput' }}.click()">Browse</x-button>
        <ul class="mt-4 w-full text-left" x-show="files.length > 0">
            <template x-for="file in files" :key="file.name">
                <li class="text-sm text-gray-700 truncate" x-text="file.name"></li>
            </template>
        </ul>
    </div>
    <div class="contents" x-show="isFileDragging">
        <p class="text-gray-600">
            Drop here to upload file.
        </p>
        <x-icon class="text-gray-600">
            upload
        </x-icon>
    </div>
</div>
