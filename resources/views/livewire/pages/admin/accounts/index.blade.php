<div class="contents">
    <x-sections.header title="Accounts">
        <x-button id="addSubjectBtn" wire:click='openForm'>
            Add Account
        </x-button>
    </x-sections.header>

    <!-- Main Dashboard Content -->
    <div class="main-dash grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Users and Completed Tasks -->

        <!-- Responsive Table -->
        <div class="col-span-1 md:col-span-3 overflow-auto">
            @php
                $columns = [
                    ['label' => 'Name', 'render' => 'name'],
                    ['label' => 'Email', 'render' => 'email'],
                    ['label' => 'Role', 'render' => 'role.display_name'],
                    [
                        'label' => 'Actions',
                        'render' => 'blade:table.actions',
                        'props' => [
                            'actions' => [
                                'edit_password' => [
                                    'order' => 1.1,
                                    'label' => 'Edit Password',
                                    'color' => 'primary',
                                    'wire:click' => fn($data) => "editPassword({$data->id})",
                                ],
                                'archive' => [
                                    'condition' => fn($data) => !$data->is_archived && !$data->isCurrentUser(),
                                ],
                                'delete' => [
                                    'condition' => fn($data) => !$data->hasDependents() && !$data->isCurrentUser(),
                                ],
                            ],
                        ],
                    ],
                ];
            @endphp
            <x-table :columns="$columns" :data="$users">
            </x-table>
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
                    <div class="flex justify-end gap-1">
                        <x-button type="button" id="cancelBtn" wire:click='closeForm' color="neutral"
                            variant="text">Cancel</x-button>
                        <x-button type="submit">Save</x-button>
                    </div>
                </x-forms.user-form>
            </div>
        </div>
    @endif
</div>
