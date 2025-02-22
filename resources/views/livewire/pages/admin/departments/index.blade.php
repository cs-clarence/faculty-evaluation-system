<div class="contents">
    <x-sections.header title="Departments">
        <x-button id="addSubjectBtn" wire:click='openForm'>
            Add Department
        </x-button>
    </x-sections.header>

    <!-- Main Dashboard Content -->
    <div class="main-dash grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Users and Completed Tasks -->
        @php
            $columns = [
                ['label' => 'Department Code', 'render' => 'code'],
                ['label' => 'Department Name', 'render' => 'name'],
                ['label' => 'Courses', 'render' => 'courses_count'],
                ['label' => 'Actions', 'render' => 'blade:table.actions'],
            ];
        @endphp

        <!-- Responsive Table -->
        <div class="col-span-1 md:col-span-3 overflow-auto">
            <x-table :data="$departments" :columns="$columns" :paginate="15">
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
            <x-dialog el="form" wire:submit.prevent="save" wire:key="department-form">
                <x-dialog.title>
                    @isset($department)
                        Edit Department
                    @else
                        Add New Department
                    @endisset
                </x-dialog.title>
                <x-dialog.content>

                    @csrf
                    <input type="hidden" name="id" wire:model.defer="form.id">
                    <x-form-control>
                        <x-form-control.label key="form.code">Department Code</x-form-control.label>
                        <x-input type="text" key="form.code" required wire:model="form.code" />
                        <x-form-control.error-text key="form.code" />
                    </x-form-control>
                    <x-form-control>
                        <x-form-control.label key="form.name">Department Name</x-form-control.label>
                        <x-input type="text" key="form.name" required wire:model="form.name" />
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
    @endif
</div>
