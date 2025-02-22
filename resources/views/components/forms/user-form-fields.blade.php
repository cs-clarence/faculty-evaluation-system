@props(['role' => 'admin', 'courses' => [], 'departments' => [], 'roles' => [], 'schoolYears' => []])

@csrf
@isset($id)
    <input {{ $id->attributes->merge(['type' => 'hidden']) }}>
@endisset
@isset($roleCode)
    <x-form-control>
        @php($roleCodeName = $roleCode->attributes['name'] ?? 'form.role_code')
        <x-form-control.label :key="$roleCodeName">Role</x-form-control.label>
        <x-select :key="$roleCodeName" :attributes="$roleCode->attributes" :options="$roles" :label="fn($option) => $option->display_name" :value="fn($option) => $option->code" />
        <x-form-control.error-text :key="$roleCodeName" />
    </x-form-control>
@endisset
@isset($email)
    <x-form-control>
        @php($emailName = $email->attributes['name'] ?? 'form.email')
        <x-form-control.label :key="$emailName">Email</x-form-control.label>
        <x-input :key="$emailName" :attributes="$email->attributes->merge(['required' => 'true', 'type' => 'text'])" />
        <x-form-control.error-text :key="$emailName" />
    </x-form-control>
@endisset
@isset($name)
    <x-form-control>
        @php($nameName = $name->attributes['name'] ?? 'form.name')
        <x-form-control.label :key="$nameName">Name</x-form-control.label>
        <x-input :key="$nameName" :attributes="$name->attributes->merge(['required' => 'true', 'type' => 'text'])" />
        <x-form-control.error-text :key="$nameName" />
    </x-form-control>
@endisset
@isset($studentNumber)
    @php($studentNumberName = $studentNumber->attributes['name'] ?? 'form.student_number')
    <x-form-control>
        <x-form-control.label :key="$studentNumberName">Student Number</x-form-control.label>
        <x-input :key="$studentNumberName" :attributes="$studentNumber->attributes->merge(['type' => 'text', 'required' => 'true'])" />
        <x-form-control.error-text :key="$studentNumberName" />
    </x-form-control>
@endisset
@isset($password)
    @php($passwordName = $password->attributes['name'] ?? 'form.password')
    <x-form-control>
        <x-form-control.label :key="$passwordName">Password</x-form-control.label>
        <x-input :key="$passwordName" :attributes="$password->attributes->merge(['required' => 'true', 'type' => 'password'])" />
        <x-form-control.error-text :key="$passwordName" />
    </x-form-control>
@endisset
@isset($passwordConfirmation)
    @php($passwordConfirmationName = $passwordConfirmation->attributes['name'] ?? 'form.password_confirmation')
    <x-form-control>
        <x-form-control.label :key="$passwordConfirmationName">Confirm Password</x-form-control.label>
        <x-input :key="$passwordConfirmationName" :attributes="$passwordConfirmation->attributes->merge(['required' => 'true', 'type' => 'password'])" />
        <x-form-control.error-text :key="$passwordConfirmationName" />
    </x-form-control>
@endisset
@isset($courseId)
    @php($courseIdName = $courseId->attributes['name'] ?? 'form.course_id')
    <x-form-control>
        <x-form-control.label :key="$courseIdName">Course</x-form-control.label>
        <x-select :key="$courseIdName" :options="$courses" :value="fn($option) => $option->id" :label="fn($option) => $option->name" :attributes="$courseId->attributes->merge(['title' => 'Courses must have semesters with first year level'])"
            empty="No courses with semesters are available" placeholder="Select course" />
        <x-form-control.error-text :key="$courseIdName" />
    </x-form-control>
@endisset
@isset($startingSchoolYearId)
    @php($startingSchoolYearIdName = $startingSchoolYearId->attributes['name'] ?? 'form.starting_school_year_id')
    <x-form-control>
        <x-form-control.label :key="$startingSchoolYearIdName">Starting School Year</x-form-control.label>
        <x-select :key="$startingSchoolYearIdName" :options="$schoolYears" :value="fn($option) => $option->id" :label="fn($option) => $option->year_start . ' - ' . $option->year_end" :attributes="$startingSchoolYearId->attributes"
            placeholder="Select school year" empty="No school years are available" />
        <x-form-control.error-text :key="$startingSchoolYearIdName" />
    </x-form-control>
@endisset
@isset($realignSubjects)
    @php($realignSubjectsName = $realignSubjects->attributes['name'] ?? 'form.realign_subjects')

    <x-form-control>
        <x-row class="items-center gap-2">
            <input
                {{ $realignSubjects->attributes->merge([
                    'id' => $realignSubjectsName,
                    'type' => 'checkbox',
                    'name' => $realignSubjectsName,
                ]) }} />
            <x-form-control.label for="{{ $realignSubjectsName }}">Realign
                Subjects</x-form-control.label>
        </x-row>
        <x-form-control.error-text key="{{ $realignSubjectsName }}" />
    </x-form-control>
@endisset
@isset($deleteSubjectsFromPreviousCourse)
    @php($deleteSubjectsFromPreviousCourseName = $deleteSubjectsFromPreviousCourse->attributes['name'] ?? 'form.delete_subjects_from_previous_course')
    <x-form-control>
        <x-row class="items-center gap-2">
            <input
                {{ $deleteSubjectsFromPreviousCourse->attributes->merge([
                    'id' => $deleteSubjectsFromPreviousCourseName,
                    'type' => 'checkbox',
                    'name' => $deleteSubjectsFromPreviousCourseName,
                ]) }} />
            <x-form-control.label key="{{ $deleteSubjectsFromPreviousCourseName }}">Delete
                Subjects From Previous Course</x-form-control.label>
        </x-row>
        <x-form-control.error-text key="{{ $deleteSubjectsFromPreviousCourseName }}" />
    </x-form-control>
@endisset

@isset($departmentId)
    @php($departmentIdName = $departmentId->attributes['name'] ?? 'form.department_id')
    <x-form-control>
        <x-form-control.label :key="$departmentIdName">Department</x-form-control.label>
        <x-select :key="$departmentIdName" :options="$departments" :value="fn($option) => $option->id" :label="fn($option) => $option->name" :attributes="$departmentId->attributes"
            placeholder="Select department" />
        <x-form-control.error-text :key="$departmentIdName" />
    </x-form-control>
@endisset

@isset($active)
    @php($activeName = $active->attributes['name'] ?? 'form.active')
    <x-form-control>
        <x-row class="items-center gap-2">
            <input {{ $active->attributes->merge(['type' => 'checkbox', 'name' => $activeName, 'id' => $activeName]) }} />
            <x-form-control.label :key="$activeName">Active</x-form-control.label>
        </x-row>
        <x-form-control.error-text :key="$activeName" />
    </x-form-control>
@endisset

@isset($slot)
    {{ $slot }}
@endisset
