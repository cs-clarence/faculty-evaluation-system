@props(['role' => 'admin', 'courses' => [], 'departments' => [], 'roles' => [], 'schoolYears' => []])

<form {{ $attributes->merge(['class' => 'contents']) }}>
    @csrf
    @isset($id)
        <input {{ $id->attributes->merge(['type' => 'hidden']) }}>
    @endisset
    @isset($roleCode)
        <div class="mb-4">
            @php($roleCodeName = $roleCode->attributes['name'] ?? 'form.role_code')
            <label for="{{ $roleCodeName }}" class="block text-gray-700">Role</label>
            <select name="{{ $roleCodeName }}"
                {{ $roleCode->attributes->merge([
                    'class' => 'w-full px-3 py-2 border rounded-lg',
                ]) }}>
                <option value="" selected disabled>Select a role</option>
                @forelse ($roles as $role)
                    <option value="{{ $role->code }}">
                        {{ $role->display_name }}
                    </option>
                @empty
                    <option value="" selected disabled>No roles found</option>
                @endforelse
            </select>
            @error($roleCodeName)
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
    @endisset
    @isset($email)
        <div class="mb-4">
            @php($emailName = $email->attributes['name'] ?? 'form.email')
            <label for="{{ $emailName }}" class="block text-gray-700">Email</label>
            <input name="{{ $emailName }}"
                {{ $email->attributes->merge([
                    'class' => 'w-full px-3 py-2 border rounded-lg',
                    'required' => 'true',
                    'type' => 'text',
                ]) }}>
            @error($emailName)
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
    @endisset
    @isset($name)
        <div class="mb-4">
            @php($nameName = $name->attributes['name'] ?? 'form.name')
            <label for="{{ $nameName }}" class="block text-gray-700">Name</label>
            <input name="{{ $nameName }}"
                {{ $name->attributes->merge([
                    'class' => 'w-full px-3 py-2 border rounded-lg',
                    'type' => 'text',
                    'required' => 'true',
                ]) }}>
            @error($nameName)
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
    @endisset
    @isset($studentNumber)
        <div class="mb-4">
            @php($studentNumberName = $studentNumber->attributes['name'] ?? 'form.student_number')
            <label for="{{ $studentNumberName }}" class="block text-gray-700">Student Number</label>
            <input name="{{ $studentNumberName }}"
                {{ $studentNumber->attributes->merge([
                    'class' => 'w-full px-3 py-2 border rounded-lg',
                    'type' => 'text',
                    'required' => 'true',
                ]) }}>
            @error($studentNumberName)
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
    @endisset
    @isset($password)
        <div class="mb-4">
            @php($passwordName = $password->attributes['name'] ?? 'form.password')
            <label for="{{ $passwordName }}" class="block text-gray-700">Password</label>
            <input name="{{ $passwordName }}"
                {{ $password->attributes->merge([
                    'class' => 'w-full px-3 py-2 border rounded-lg',
                    'required' => 'true',
                    'type' => 'password',
                ]) }}>
            @error($passwordName)
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
    @endisset
    @isset($passwordConfirmation)
        <div class="mb-4">
            @php($passwordConfirmationName = $passwordConfirmation->attributes['name'] ?? 'form.password_confirmation')
            <label for="{{ $passwordConfirmationName }}" class="block text-gray-700">Confirm Password</label>
            <input name="{{ $passwordConfirmationName }}"
                {{ $passwordConfirmation->attributes->merge([
                    'class' => 'w-full px-3 py-2 border rounded-lg',
                    'required' => 'true',
                    'type' => 'password',
                ]) }}>
            @error($passwordConfirmationName)
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
    @endisset
    @isset($courseId)
        <div class="mb-4">
            @php($courseIdName = $courseId->attributes['name'] ?? 'form.course_id')
            <label for="{{ $courseIdName }}" class="block text-gray-700">Course</label>
            <select name="{{ $courseIdName }}"
                {{ $courseId->attributes->merge([
                    'class' => 'w-full px-3 py-2 border rounded-lg',
                ]) }}>
                <option value="" selected disabled>Select a course</option>
                @forelse ($courses as $course)
                    <option value="{{ $course->id }}">
                        {{ $course->name }}
                        ({{ $course->code }})
                    </option>
                @empty
                    <option value="" selected disabled>No courses found</option>
                @endforelse
            </select>
            @error($courseIdName)
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
    @endisset
    @isset($startingSchoolYearId)
        <div class="mb-4">
            @php($startingSchoolYearIdName = $startingSchoolYearId->attributes['name'] ?? 'form.starting_school_year_id')
            <label for="{{ $startingSchoolYearIdName }}" class="block text-gray-700">Starting School Year</label>
            <select name="{{ $startingSchoolYearIdName }}"
                {{ $startingSchoolYearId->attributes->merge([
                    'class' => 'w-full px-3 py-2 border rounded-lg',
                ]) }}>
                <option value="" selected disabled>Select a school year</option>
                @forelse ($schoolYears as $startingSchoolYear)
                    <option value="{{ $startingSchoolYear->id }}">
                        {{ $startingSchoolYear->year_start }} - {{ $startingSchoolYear->year_end }}
                    </option>
                @empty
                    <option value="" selected disabled>No school years found</option>
                @endforelse
            </select>
            @error($startingSchoolYearIdName)
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
    @endisset
    @isset($realignSubjects)
        @php($realignSubjectsName = $realignSubjects->attributes['name'] ?? 'form.realign_subjects')

        <div class="mb-4 flex flex-row items-center gap-2">
            <input
                {{ $realignSubjects->attributes->merge([
                    'id' => $realignSubjectsName,
                    'type' => 'checkbox',
                    'name' => $realignSubjectsName,
                ]) }} />
            <label for="{{ $realignSubjectsName }}" class="block text-sm font-medium text-gray-700">Realign
                Subjects</label>
            @error($realignSubjectsName)
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
    @endisset
    @isset($deleteSubjectsFromPreviousCourse)
        @php($deleteSubjectsFromPreviousCourseName = $deleteSubjectsFromPreviousCourse->attributes['name'] ?? 'form.delete_subjects_from_previous_course')
        <div class="mb-4 flex flex-row items-center gap-2">
            <input
                {{ $deleteSubjectsFromPreviousCourse->attributes->merge([
                    'id' => $deleteSubjectsFromPreviousCourseName,
                    'type' => 'checkbox',
                    'name' => $deleteSubjectsFromPreviousCourseName,
                ]) }} />
            <label for="{{ $deleteSubjectsFromPreviousCourseName }}" class="block text-sm font-medium text-gray-700">Delete
                Subjects From Previous Course</label>
            @error($deleteSubjectsFromPreviousCourseName)
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
    @endisset
    @isset($departmentId)
        <div class="mb-4">
            @php($departmentIdName = $departmentId->attributes['name'] ?? 'form.department_id')
            <label for="{{ $departmentIdName }}" class="block text-gray-700">Department</label>
            <select name="{{ $departmentIdName }}"
                {{ $departmentId->attributes->merge([
                    'class' => 'w-full px-3 py-2 border rounded-lg',
                ]) }}>
                <option value="" selected disabled>Select a department</option>
                @forelse ($departments as $department)
                    <option value="{{ $department->id }}">
                        {{ $department->name }}
                        ({{ $department->code }})
                    </option>
                @empty
                    <option value="" selected disabled>No departments found</option>
                @endforelse
            </select>
            @error($departmentIdName)
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
    @endisset

    @isset($slot)
        {{ $slot }}
    @endisset
</form>
