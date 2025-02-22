<div class="contents">
    <x-sections.header title="Forms">
        <x-button id="addSubjectBtn" wire:click='openForm'>
            Add Form
        </x-button>
    </x-sections.header>

    <!-- Main Dashboard Content -->
    <div class="main-dash grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Users and Completed Tasks -->

        <!-- Responsive Table -->
        <div class="col-span-1 md:col-span-3 overflow-auto">
            @php
                $columns = [
                    ['label' => 'Form Name', 'render' => 'name'],
                    ['label' => 'Description', 'render' => 'description'],
                    ['label' => 'Sections', 'render' => 'sections_count'],
                    ['label' => 'Questions', 'render' => 'questions_count'],
                    [
                        'label' => 'Actions',
                        'render' => 'blade:table.actions',
                        'props' => [
                            'actions' => [
                                'view' => [
                                    'type' => 'link',
                                    'order' => 1.1,
                                    'label' => 'View',
                                    'href' => fn($data) => route('admin.forms.form', [
                                        'form' => $data,
                                    ]),
                                ],
                                'duplicate' => [
                                    'order' => 5,
                                    'variant' => 'outlined',
                                    'label' => 'Duplicate',
                                    'wire:click' => fn($data) => "duplicate({$data->id})",
                                ],
                            ],
                        ],
                    ],
                ];
            @endphp
            <x-table :data="$forms" :$columns>
                <x-slot:actions>
                    <x-search wire:input.debounce.500ms="search(search)" :value="$searchText" />
                </x-slot:actions>
            </x-table>
        </div>
    </div>

    <!-- Add Subject Modal -->
    @if ($isFormOpen)
        <div id="addSubjectModal" class="fixed inset-0 bg-gray-900/50 flex justify-center items-center"
            wire:click.self='closeForm'>
            <div class="bg-white p-6 rounded-lg w-96">
                @isset($model)
                    <h3 class="text-lg font-semibold mb-4">Edit Form</h3>
                @else
                    <h3 class="text-lg font-semibold mb-4">Add New Form</h3>
                @endisset

                <!-- Add Subject Form -->
                <form wire:submit.prevent="save">
                    @csrf
                    <input type="hidden" name="id" wire:model.defer="form.id">
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700">Form Name</label>
                        <input type="text" name="name" id="subjectName" required
                            class="w-full px-3 py-2 border rounded-lg" wire:model.defer="form.name">
                        @error('form.name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block text-gray-700">Description</label>
                        <textarea name="description" required class="w-full px-3 py-2 border rounded-lg" wire:model.defer="form.description">
                        </textarea>
                        @error('form.description')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex justify-end gap-1">
                        <x-button type="button" id="cancelBtn" wire:click='closeForm' color="neutral"
                            variant="text">Cancel</x-button>
                        <x-button type="submit">Save</x-button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
