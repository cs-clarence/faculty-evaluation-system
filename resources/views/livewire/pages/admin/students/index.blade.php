@php
    use App\Models\RoleCode;
@endphp
<div class="contents">
    <div class="top flex justify-end mb-4">
        <button id="addSubjectBtn" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600"
            wire:click='openForm'>
            Add Student
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
                        <th class="py-3 px-4 text-left text-sm font-semibold">Student Number</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Name</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Email</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Course</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Batch School Year</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Semesters</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Subjects</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse($students as $student)
                        <tr>
                            <td class="py-3 px-4 border-b">{{ $student->student->student_number }}</td>
                            <td class="py-3 px-4 border-b">{{ $student->name }}</td>
                            <td class="py-3 px-4 border-b">{{ $student->email }}</td>
                            <td class="py-3 px-4 border-b">{{ $student->student->course->name }}
                                ({{ $student->student->course->code }})
                            </td>
                            <td class="py-3 px-4 border-b">{{ $student->student->schoolYear }}</td>
                            <td class="py-3 px-4 border-b">{{ $student->student->student_semesters_count }}</td>
                            <td class="py-3 px-4 border-b">{{ $student->student->student_subjects_count }}</td>
                            <td class="py-3 px-4 border-b">
                                <button wire:click='edit({{ $student->id }})'
                                    class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                                    Edit
                                </button>
                                <button wire:click='editPassword({{ $student->id }})'
                                    class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                                    Edit Password
                                </button>
                                @if ($student->is_archived)
                                    <button wire:click='unarchive({{ $student->id }})'
                                        class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                                        Unarchive
                                    </button>
                                @else
                                    <button wire:click='archive({{ $student->id }})'
                                        class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600"
                                        title="This department has courses associated with it. You can only archive it until you delete those courses.">
                                        Archive
                                    </button>
                                @endif
                                @if (!$student->hasDependents() && !$student->isCurrentUser())
                                    <button wire:click='delete({{ $student->id }})'
                                        wire:confirm='Are you sure you want to delete this teacher?'
                                        class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">
                                        Delete
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-3 px-4 text-center text-gray-500">No students found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($this->isFormOpen)
        <div id="addSubjectModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center"
            wire:click.self='closeForm'>
            <div class="bg-white p-6 rounded-lg w-96">
                @isset($this->model)
                    <h3 class="text-lg font-semibold mb-4">Edit Student</h3>
                @else
                    <h3 class="text-lg font-semibold mb-4">Add New Student</h3>
                @endisset

                <x-forms.user-form wire:submit.prevent="save" :courses="$courses" :schoolYears="$schoolYears">
                    @if ($this->form->include_base)
                        <input type="hidden" name="role_code" value="{{ RoleCode::Student->value }}"
                            wire:model="form.role_code">

                        <x-slot:id name="form.id" wire:model="form.id"></x-slot:id>
                        <x-slot:name name="form.name" wire:model="form.name"></x-slot:name>
                        <x-slot:email name="form.email" wire:model="form.email"></x-slot:email>
                        <x-slot:studentNumber name="form.student_number" wire:model="form.student_number"></x-slot:studentNumber>
                        <x-slot:courseId name="form.course_id" wire:model.live="form.course_id"></x-slot:courseId>
                        <x-slot:startingSchoolYearId name="form.starting_school_year_id"
                            wire:model.live="form.starting_school_year_id"></x-slot:startingSchoolYearId>

                        @isset($this->model)
                            @if (
                                $this->form->course_id !== $this->model->student->course_id ||
                                    $this->form->starting_school_year_id !== $this->model->student->starting_school_year_id)
                                <x-slot:realignSubjects name="form.realign_subjects"
                                    wire:model="form.realign_subjects"></x-slot:realignSubjects>
                            @endif
                            @if ($this->form->course_id !== $this->model->student->course_id)
                                <x-slot:deleteSubjectsFromPreviousCourse name="form.delete_subjects_from_previous_course"
                                    wire:model="form.delete_subjects_from_previous_course"></x-slot:deleteSubjectsFromPreviousCourse>
                            @endif
                        @endisset
                    @endif
                    @if ($this->form->include_password)
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
