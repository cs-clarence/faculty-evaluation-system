@php
    use App\Models\RoleCode;
@endphp

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
                    ['label' => 'Active', 'render' => fn($data) => $data->active ? 'Yes' : 'No'],
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
                                'activate' => [
                                    'order' => 1.2,
                                    'condition' => fn($data) => !$data->active && !$data->isCurrentUser(),
                                    'label' => 'Activate',
                                    'wire:click' => fn($data) => "activate({$data->id})",
                                    'variant' => 'outlined',
                                    'color' => 'primary',
                                ],
                                'deactivate' => [
                                    'order' => 1.2,
                                    'condition' => fn($data) => $data->active && !$data->isCurrentUser(),
                                    'label' => 'Deactivate',
                                    'wire:click' => fn($data) => "deactivate({$data->id})",
                                    'wire:confirm' => 'Are you sure you want to deactivate this user?',
                                    'variant' => 'outlined',
                                    'color' => 'danger',
                                ],
                                'archive' => [
                                    // 'condition' => fn($data) => !$data->is_archived && !$data->isCurrentUser(),
                                    'condition' => fn($data) => false,
                                    'wire:confirm' => 'Are you sure you want to archive this user?',
                                ],
                                'unarchive' => [
                                    'condition' => fn($data) => false,
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
                <x-slot:actions>
                    <x-search wire:input.debounce.500ms="search(search)" :value="$searchText" />
                </x-slot:actions>
            </x-table>
        </div>
    </div>

    <!-- Add Subject Modal -->
    @if ($isFormOpen)
        <x-modal-scrim />
        <x-dialog.container wire:click.self='closeForm'>
            <x-dialog el="form" wire:submit.prevent="save" wire:key="account-form">
                <x-dialog.title>
                    @isset($model)
                        @if ($form->include_base && !$form->include_password)
                            Edit Account
                        @elseif ($form->include_password)
                            Edit Account Password
                        @endif
                    @else
                        Add New Account
                    @endisset
                </x-dialog.title>
                <x-dialog.content>
                    <x-forms.user-form-fields :departments="$departments" :roles="$roles" :courses="$courses" :schoolYears="$schoolYears">
                        @if ($form->include_base)
                            <x-slot:id name="form.id" wire:model="form.id"></x-slot:id>
                            <x-slot:roleCode name="form.role_code" wire:model.live='form.role_code'></x-slot:roleCode>
                            <x-slot:name name="form.name" wire:model="form.name"></x-slot:name>
                            <x-slot:email name="form.email" wire:model="form.email"></x-slot:email>
                            @if ($form->role_code === RoleCode::Teacher->value || $form->role_code === RoleCode::Dean->value)
                                <x-slot:departmentId name="form.department_id" wire:model="form.department_id"></x-slot:departmentId>
                            @endif
                            @if ($form->role_code === RoleCode::Student->value)
                                <x-slot:courseId name="form.course_id" wire:model="form.course_id"></x-slot:courseId>
                                <x-slot:studentNumber name="form.student_number"
                                    wire:model="form.student_number"></x-slot:studentNumber>
                                <x-slot:startingSchoolYearId name="form.starting_school_year_id"
                                    wire:model="form.starting_school_year_id"></x-slot:startingSchoolYearId>
                            @endif
                        @endif
                        @if ($form->include_password)
                            <x-slot:password name="form.password" wire:model="form.password"></x-slot:password>
                            <x-slot:passwordConfirmation name="form.password_confirmation"
                                wire:model="form.password_confirmation">
                            </x-slot:passwordConfirmation>
                        @endif
                        @if ($form->include_base && !$model?->isCurrentUser())
                            <x-slot:active wire:model="form.active"></x-slot:active>
                        @endif
                        <x-dialog.actions>
                            <x-button type="button" id="cancelBtn" wire:click='closeForm' color="neutral"
                                variant="text">Cancel</x-button>
                            <x-button type="submit">Save</x-button>
                        </x-dialog.actions>
                    </x-forms.user-form-fields>
                </x-dialog.content>
            </x-dialog>
        </x-dialog.container>
    @endif
</div>
