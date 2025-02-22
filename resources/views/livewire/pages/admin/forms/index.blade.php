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
        <x-modal-scrim />
        <x-dialog.container wire:click.self="closeForm">
            <x-dialog el="form" wire:key="form-form" wire:submit.prevent="save">
                <x-dialog.title>
                    @isset($model)
                        Edit Form
                    @else
                        Add New Form
                    @endisset
                </x-dialog.title>
                <x-dialog.content>
                    @csrf
                    <input type="hidden" name="id" wire:model.defer="form.id">
                    <x-form-control>
                        <x-form-control.label key="form.name">Form Name</x-form-control.label>
                        <x-input key="form.name" required wire:model="form.name" />
                        <x-form-control.error-text key="form.name" />
                    </x-form-control>
                    <x-form-control>
                        <x-form-control.label key="form.description">Description</x-form-control.label>
                        <x-textarea key="form.description" required wire:model="form.description" />
                        <x-form-control.error-text key="form.description" />
                    </x-form-control>
                </x-dialog.content>
                <x-dialog.actions>
                    <x-button type="button" id="cancelBtn" wire:click='closeForm' color="neutral"
                        variant="text">Cancel</x-button>
                    <x-button type="submit">Save</x-button>
                </x-dialog.actions>
            </x-dialog>
        </x-dialog.container>
    @endif
</div>
