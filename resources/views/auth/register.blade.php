<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Role -->
        <div class="mt-4">
            <x-input-label for="role_id" :value="__('Role')" />
            <select id="role_id" name="role_id" class="block mt-1 w-full" onchange="toggleStudentFields()">
                @can('super_admin_area')
                    <option value="1">SuperAdmin</option>
                @endcan
                <option value="2">Admin</option>
                <option value="3">Student</option>
                <option value="4">Teacher</option>
            </select>
            <x-input-error :messages="$errors->get('role_id')" class="mt-2" />
        </div>

        <!-- Student Details -->
        <div id="student-details" class="mt-4 hidden">
            <div>
                <x-input-label for="student_id" :value="__('Student ID')" />
                <x-text-input id="student_id" class="block mt-1 w-full" type="text" name="student_id" :value="old('student_id')" />
                <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
            </div>
            <div class="mt-4">
                <x-input-label for="studentName" :value="__('Student Name')" />
                <x-text-input id="studentName" class="block mt-1 w-full" type="text" name="studentName" :value="old('studentName')" />
                <x-input-error :messages="$errors->get('studentName')" class="mt-2" />
            </div>
            <div class="mt-4">
                <x-input-label for="address" :value="__('Address')" />
                <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')" />
                <x-input-error :messages="$errors->get('address')" class="mt-2" />
            </div>
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                          type="password"
                          name="password"
                          required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                          type="password"
                          name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        function toggleStudentFields() {
            const roleSelect = document.getElementById('role_id');
            const studentDetails = document.getElementById('student-details');
            if (roleSelect.value == '3') { // Role ID 3 is Student
                studentDetails.classList.remove('hidden');
            } else {
                studentDetails.classList.add('hidden');
            }
        }
    </script>
</x-guest-layout>
