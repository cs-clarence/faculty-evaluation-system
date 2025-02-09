<div class="contents">
    <div class="top flex justify-end mb-4">
        <button id="addSubjectBtn" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600"
            wire:click='openForm'>
            Add Account
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
                        <th class="py-3 px-4 text-left text-sm font-semibold">Name</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Email</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Role</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse($users as $user)
                        <tr wire:key="{{ $user->id }}">
                            <td class="py-3 px-4 border-b">{{ $user->name }}</td>
                            <td class="py-3 px-4 border-b">{{ $user->email }}</td>
                            <td class="py-3 px-4 border-b">{{ $user->role->display_name }}</td>
                            <td class="py-3 px-4 border-b">
                                <button wire:click='edit({{ $user->id }})'
                                    class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                                    Edit
                                </button>
                                <button wire:click='editPassword({{ $user->id }})'
                                    class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                                    Edit Password
                                </button>
                                @if ($user->is_archived)
                                    <button wire:click='unarchive({{ $user->id }})'
                                        class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                                        Unarchive
                                    </button>
                                @else
                                    @if (!$user->isCurrentUser())
                                        <button wire:click='archive({{ $user->id }})'
                                            class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600"
                                            title="This department has courses associated with it. You can only archive it until you delete those courses.">
                                            Archive
                                        </button>
                                    @endif
                                @endif
                                @if (!$user->hasDependents() && !$user->isCurrentUser())
                                    <button wire:click='delete({{ $user->id }})'
                                        wire:confirm='Are you sure you want to delete this user?'
                                        class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">
                                        Delete
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-3 px-4 text-center text-gray-500">No users found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Subject Modal -->
    @if ($isFormOpen)
        <div id="addSubjectModal" class="fixed inset-0 bg-gray-900/50 flex justify-center items-center"
            wire:click.self='closeForm'>
            <div class="bg-white p-6 rounded-lg w-96">
                @isset($model)
                    @if ($form->include_base && !$form->include_password)
                        <h3 class="text-lg font-semibold mb-4">Edit Account</h3>
                    @elseif ($form->include_password)
                        <h3 class="text-lg font-semibold mb-4">Edit Account Password</h3>
                    @endif
                @else
                    <h3 class="text-lg font-semibold mb-4">Add New Account</h3>
                @endisset

                <x-forms.user-form wire:submit.prevent="save" :departments="$departments" :roles="$roles" :courses="$courses"
                    :schoolYears="$schoolYears">
                    @if ($form->include_base)
                        <x-slot:id name="form.id" wire:model="form.id"></x-slot:id>
                        <x-slot:roleCode name="form.role_code" wire:model.live='form.role_code'></x-slot:roleCode>
                        <x-slot:name name="form.name" wire:model="form.name"></x-slot:name>
                        <x-slot:email name="form.email" wire:model="form.email"></x-slot:email>
                        @if ($form->role_code === 'teacher')
                            <x-slot:departmentId name="form.department_id" wire:model="form.department_id"></x-slot:departmentId>
                        @endif
                        @if ($form->role_code === 'student')
                            <x-slot:courseId name="form.course_id" wire:model="form.course_id"></x-slot:courseId>
                            <x-slot:studentNumber name="form.student_number" wire:model="form.student_number"></x-slot:studentNumber>
                            <x-slot:startingSchoolYearId name="form.starting_school_year_id"
                                wire:model="form.starting_school_year_id"></x-slot:startingSchoolYearId>
                        @endif
                    @endif
                    @if ($form->include_password)
                        <x-slot:password name="form.password" wire:model="form.password"></x-slot:password>
                        <x-slot:passwordConfirmation name="form.password_confirmation" wire:model="form.password_confirmation">
                        </x-slot:passwordConfirmation>
                    @endif
                    <div class="flex justify-end">
                        <button type="button" id="cancelBtn" wire:click='closeForm'
                            class="px-4 py-2 mr-2 text-gray-500 hover:text-gray-700">Cancel</button>
                        <button type="submit"
                            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Save</button>
                    </div>
                </x-forms.user-form>
            </div>
        </div>
    @endif
</div>
