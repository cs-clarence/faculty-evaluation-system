<div class="contents">
    <x-sections.header title="School Years">
        <x-button wire:click='openForm' id="addButton">
            Add School Year
        </x-button>
    </x-sections.header>

    <!-- Main Dashboard Content -->
    <div class="main-dash grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Users and Completed Tasks -->

        <!-- Responsive Table -->
        <div class="col-span-1 md:col-span-3 overflow-auto">
            @php
                $columns = [
                    ['label' => 'School Year', 'render' => fn($data) => $data->year_start . ' - ' . $data->year_end],
                    ['label' => 'Semesters', 'render' => 'semesters_count'],
                    [
                        'label' => 'Actions',
                        'render' => 'blade:table.actions',
                        'props' => [
                            'actions' => [
                                'archive' => [
                                    'condition' => false,
                                ],
                                'unarchive' => [
                                    'condition' => false,
                                ],
                            ],
                        ],
                    ],
                ];
            @endphp

            <x-table :data="$schoolYears" :columns="$columns" :paginate="15" />
        </div>
    </div>

    @if ($isFormOpen)
        <x-modal-scrim />
        <x-dialog.container wire:click.self='closeForm'>
            <x-dialog el="form" wire:submit.prevent='save' wire:key='school-year-form'>
                <x-dialog.title>
                    @isset($schoolYear)
                        Edit School Year
                    @else
                        Add New School Year
                    @endisset
                </x-dialog.title>
                @csrf
                <input type="hidden" name="id" wire:model.defer="form.id">
                <x-form-control>
                    <x-form-control.label key="form.year_start">
                        Year Start
                    </x-form-control.label>
                    <x-input key="form.year_start" type="text" required type="number"
                        wire:model.blur="form.year_start" :disabled="$schoolYear->hasDependents()" />
                    <x-form-control.error-text key="form.year_start" />
                </x-form-control>
                {{-- <div class="mb-4">
                    <label for="year_end" class="block text-gray-700">Year End</label>
                    @error('form.year_end')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div> --}}
                <x-form-control>
                    <x-form-control.label key="form.year_end">
                        Year End
                    </x-form-control.label>

                    <x-input type="text" key="form.year_end" required type="number" wire:model.defer="form.year_end"
                        disabled />
                    <x-form-control.error-text key="form.year_end" />
                </x-form-control>
                <x-form-control>
                    <x-form-control.label key="form.semesters">
                        Semesters
                    </x-form-control.label>
                    <x-input type="text" key="form.semesters" required type="number"
                        wire:model.defer="form.semesters" />
                    <x-form-control.error-text key="form.semesters" />
                </x-form-control>

                <x-dialog.actions>
                    <x-button type="button" id="cancelBtn" wire:click='closeForm' color="neutral"
                        variant="text">Cancel</x-button>
                    <x-button type="submit">Save</x-button>
                </x-dialog.actions>
            </x-dialog>
        </x-dialog.container>
    @endif
</div>
