<div class="contents">
    <div class="top flex justify-end mb-4">
        <button id="addSubjectBtn" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600"
            wire:click='openForm'>
            Add Department
        </button>
    </div>

    <!-- Main Dashboard Content -->
    <div class="main-dash grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Users and Completed Tasks -->

        <!-- Responsive Table -->
        <div class="col-span-1 md:col-span-3 overflow-auto">
            <table class="min-w-full bg-white rounded-lg overflow-hidden shadow-md">
                <thead class="bg-gray-200 text-gray-600">
                    <tr>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Department Code</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Department Name</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Courses</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse($departments as $department)
                        <tr>
                            <td class="py-3 px-4 border-b">{{ $department->code }}</td>
                            <td class="py-3 px-4 border-b">{{ $department->name }}</td>
                            <td class="py-3 px-4 border-b">{{ $department->courses_count }}</td>
                            <td class="py-3 px-4 border-b">
                                <button wire:click='edit({{ $department->id }})'
                                    class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                                    Edit
                                </button>
                                @if ($department->hasDependents())
                                    @isset($department->archived_at)
                                        <button wire:click='unarchive({{ $department->id }})'
                                            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                                            Unarchive
                                        </button>
                                    @else
                                        <button wire:click='archive({{ $department->id }})'
                                            class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600"
                                            title="This department has courses associated with it. You can only archive it until you delete those courses.">
                                            Archive
                                        </button>
                                    @endisset
                                @else
                                    <button wire:click='delete({{ $department->id }})'
                                        wire:confirm='Are you sure you want to delete this department?'
                                        class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">
                                        Delete
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-3 px-4 text-center text-gray-500">No departments found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Subject Modal -->
    @if ($this->isFormOpen)
        <div id="addSubjectModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center"
            wire:click.self='closeForm'>
            <div class="bg-white p-6 rounded-lg w-96">
                @isset($this->department)
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
                    <div class="flex justify-end">
                        <button type="button" id="cancelBtn" wire:click='closeForm'
                            class="px-4 py-2 mr-2 text-gray-500 hover:text-gray-700">Cancel</button>
                        <button type="submit"
                            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Save</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
