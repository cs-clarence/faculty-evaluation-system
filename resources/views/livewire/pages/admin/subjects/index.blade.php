<div class="contents">
    <x-sections.header title="Subjects">
        <x-button wire:click='openForm' id="addSubjectBtn">
            Add Subject
        </x-button>
    </x-sections.header>

    <!-- Main Dashboard Content -->
    <div class="main-dash grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Users and Completed Tasks -->

        <!-- Responsive Table -->
        <div class="col-span-1 md:col-span-3 overflow-auto">
            @php
                $columns = [
                    ['label' => 'Subject Code', 'render' => 'code'],
                    ['label' => 'Subject Name', 'render' => 'name'],
                    [
                        'label' => 'Actions',
                        'render' => 'blade:table.actions',
                        'props' => [
                            'actions' => [],
                        ],
                    ],
                ];
            @endphp
            <x-table :data="$subjects" :columns="$columns" :paginate="15">
                <x-slot:actions>
                    <x-search wire:input="search(search)" :value="$searchText" />
                </x-slot:actions>
            </x-table>
        </div>

    </div>

    <!-- Add Subject Modal -->
    @if ($isFormOpen)
        <x-modal-scrim />
        <x-dialog.container wire:click.self='closeForm'>
            <x-dialog wire:key="subject-form" el="form" wire:submit.prevent="save">
                <x-dialog.title>
                    @isset($subject)
                        Edit Subject
                    @else
                        Add New Subject
                    @endisset
                </x-dialog.title>
                <x-dialog.content>
                    <input type="hidden" name="id" wire:model.defer="form.subjectId">
                    @csrf
                    <x-form-control>
                        <x-form-control.label key="form.code">Subject Code</x-form-control.label>
                        <x-input key="form.code" required wire:model="form.code" />
                        <x-form-control.error-text key="form.code" />
                    </x-form-control>
                    <x-form-control>
                        <x-form-control.label key="form.name">Subject Name</x-form-control.label>
                        <x-input key="form.name" required wire:model="form.name" />
                        <x-form-control.error-text key="form.name" />
                    </x-form-control>
                </x-dialog.content>
                <x-dialog.actions>
                    <x-button type="button" id="cancelBtn" wire:click='closeForm' color="neutral"
                        variant="text">Cancel</x-button>
                    <x-button type="submit">Save</x-button>
                </x-dialog.actions>
            </x-dialog>
        </x-dialog.container>
    @endisset
</div>
