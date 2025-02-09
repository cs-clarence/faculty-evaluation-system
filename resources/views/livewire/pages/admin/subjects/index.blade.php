<div class="contents">
    <div class="top flex justify-end mb-4">
        <button wire:click='openForm' id="addSubjectBtn"
            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
            Add Subject
        </button>
    </div>

    <!-- Main Dashboard Content -->
    <div class="main-dash grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Users and Completed Tasks -->

        <!-- Responsive Table -->
        <div class="col-span-1 md:col-span-3 overflow-auto">
            @php
                $columns = [
                    ['label' => 'Subject Code', 'render' => 'code'],
                    ['label' => 'Subject Name', 'render' => 'name'],
                    ['label' => 'Actions', 'render' => 'blade:table.actions'],
                ];
            @endphp
            <x-table :data="$subjects" :columns="$columns">
            </x-table>
        </div>

    </div>

    <!-- Add Subject Modal -->
    @if ($isFormOpen)
        <div id="addSubjectModal" class="fixed inset-0 bg-gray-900/50 flex justify-center items-center"
            wire:click.self='closeForm'>
            <div class="bg-white p-6 rounded-lg w-96">
                @isset($subject)
                    <h3 class="text-lg font-semibold mb-4">Edit Subject</h3>
                @else
                    <h3 class="text-lg font-semibold mb-4">Add New Subject</h3>
                @endisset

                <!-- Add Subject Form -->
                <form wire:submit.prevent="save">
                    @csrf
                    <input type="hidden" name="id" wire:model.defer="form.subjectId">
                    <div class="mb-4">
                        <label for="code" class="block text-gray-700">Subject Code</label>
                        <input type="text" name="code" id="subjectID" required
                            class="w-full px-3 py-2 border rounded-lg" wire:model.defer="form.code">
                        @error('form.code')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700">Subject Name</label>
                        <input type="text" name="name" id="name" required
                            class="w-full px-3 py-2 border rounded-lg" wire:model.defer="form.name">
                        @error('form.name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex justify-end">
                        <button type="button" id="cancelBtn" class="px-4 py-2 mr-2 text-gray-500 hover:text-gray-700"
                            wire:click='closeForm'>Cancel</button>
                        <button type="submit"
                            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Save</button>
                    </div>
                </form>
            </div>
        </div>
    @endisset
</div>
