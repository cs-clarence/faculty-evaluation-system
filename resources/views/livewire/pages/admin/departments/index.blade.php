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
            <x-table :data="$departments" :columns="$columns">
            </x-table>
        </div>
    </div>

    <!-- Add Subject Modal -->
    @if ($isFormOpen)
        <div id="addSubjectModal" class="fixed inset-0 bg-gray-900/50 flex justify-center items-center"
            wire:click.self='closeForm'>
            <div class="bg-white p-6 rounded-lg w-96">
                @isset($department)
                    <h3 class="text-lg font-semibold mb-4">Edit Department</h3>
                @else
                    <h3 class="text-lg font-semibold mb-4">Add New Department</h3>
                @endisset

                <!-- Add Subject Form -->
                <form wire:submit.prevent="save">
                    @csrf
                    <input type="hidden" name="id" wire:model.defer="form.id">
                    <div class="mb-4">
                        <label for="code" class="block text-gray-700">Department Code</label>
                        <input type="text" name="code" id="subjectID" required
                            class="w-full px-3 py-2 border rounded-lg" wire:model.defer="form.code">
                        @error('form.code')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700">Department Name</label>
                        <input type="text" name="name" id="subjectName" required
                            class="w-full px-3 py-2 border rounded-lg" wire:model.defer="form.name">
                        @error('form.name')
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
