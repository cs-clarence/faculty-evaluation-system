@php
    use App\Models\RoleCode;
@endphp
<x-layouts.guest>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <!-- Role -->
        <div class="mt-4">
            <x-input-label for="role_code" :value="__('Role')" />
            <select id="role_code" name="role_code" class="block mt-1 w-full" onchange="toggleStudentFields()">
                <option value="" disabled>Select Role</option>
                @forelse ($roles as $role)
                    @if (!$role->hidden)
                        <option value="{{ $role->code }}">{{ $role->display_name }}</option>
                    @endif
                @empty
                    <option value="" disabled>No roles</option>
                @endforelse
            </select>
            <x-input-error :messages="$errors->get('role_code')" class="mt-2" />
        </div>

        <!-- Name -->
        <div class="mt-4">
            <x-input-label for="name" :value="__('Full Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')"
                required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Student Details -->
        <div id="student-details" class="hidden">
            <div class="mt-4">
                <div>
                    <x-input-label for="student_number" :value="__('Student Number')" />
                    <x-text-input id="student_number" class="block w-full" type="text" name="student_number"
                        :value="old('student_number')" />
                    <x-input-error :messages="$errors->get('student_number')" class="mt-2" />
                </div>
            </div>
            <div class="mt-4">
                <x-input-label for="course_id" :value="__('Course')" />
                <select id="course_id" name="course_id" class="block w-full">
                    <option value="" disabled selected>Select Course</option>
                    @forelse ($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                    @empty
                        <option value="" disabled>No courses</option>
                    @endforelse
                </select>
                <x-input-error :messages="$errors->get('course_id')" class="mt-2" />
            </div>
            <div class="mt-4">
                <x-input-label for="starting_school_year_id" :value="__('Batch Starting School Year')" />
                <select id="starting_school_year_id" name="starting_school_year_id" class="block w-full">
                    <option value="" disabled selected>Select School Year</option>
                    @forelse ($schoolYears as $schoolYear)
                        <option value="{{ $schoolYear->id }}">{{ $schoolYear }}</option>
                    @empty
                        <option value="" disabled>No school years</option>
                    @endforelse
                </select>
                <x-input-error :messages="$errors->get('starting_school_year_id')" class="mt-2" />
            </div>
        </div>

        <div id="department-details" class="hidden">
            <div class="mt-4">
                <x-input-label for="department_id" :value="__('Department')" />
                <select id="deparment_id" name="department_id" class="block w-full">
                    <option value="" disabled selected>Select Department</option>
                    @forelse ($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @empty
                        <option value="" disabled>No departments</option>
                    @endforelse
                </select>
                <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
            </div>
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        function toggleStudentFields() {
            const roleSelect = document.getElementById('role_code');
            const studentDetails = document.getElementById('student-details');
            if (roleSelect.value === '{{ RoleCode::Student->value }}') {
                studentDetails.classList.remove('hidden');
            } else {
                studentDetails.classList.add('hidden');
            }
            const deptDetails = document.getElementById('department-details');
            if (roleSelect.value === '{{ RoleCode::Teacher->value }}' || roleSelect.value ===
                '{{ RoleCode::Dean->value }}') {
                deptDetails.classList.remove('hidden');
            } else {
                deptDetails.classList.add('hidden');
            }
        }
        toggleStudentFields();
    </script>
</x-layouts.guest>
