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
                                    'condition' => fn($data) => false,
                                ],
                                'unarchive' => [
                                    'condition' => fn($data) => false,
                                ],
                            ],
                        ],
                    ],
                ];
            @endphp

            <x-table :data="$schoolYears" :columns="$columns" :paginate="15">
            </x-table>
        </div>
    </div>

    @if ($isFormOpen)
        <div class="fixed inset-0 bg-gray-900/50 flex justify-center items-center" wire:click.self='closeForm'>
            <div class="bg-white p-6 rounded-lg w-96">
                @isset($schoolYear)
                    <h3 class="text-lg font-semibold mb-4">Edit School Year</h3>
                @else
                    <h3 class="text-lg font-semibold mb-4">Add New School Year</h3>
                @endisset
                <form id="addForm" wire:submit.prevent='save'>
                    @csrf
                    <input type="hidden" name="id" wire:model.defer="form.id">
                    <div class="mb-4">
                        <label for="year_start" class="block text-gray-700">Year Start</label>
                        <input type="text" name="year_start" id="year_start" required type="number"
                            class="w-full px-3 py-2 border rounded-lg" wire:model.blur="form.year_start">
                        @error('form.year_start')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="year_end" class="block text-gray-700">Year End</label>
                        <input type="text" name="year_end" id="year_end" required
                            class="w-full px-3 py-2 border rounded-lg opacity-50" type="number"
                            wire:model.defer="form.year_end" disabled>
                        @error('form.year_end')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="semesters" class="block text-gray-700">Semesters</label>
                        <input type="text" name="semesters" id="semesters" required type="number"
                            class="w-full px-3 py-2 border rounded-lg" wire:model.defer="form.semesters">
                        @error('form.semesters')
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
