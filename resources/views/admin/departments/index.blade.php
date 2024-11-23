@extends('layouts.admin')
@section('content')
    <div class="top flex justify-end mb-4">
        <button id="addSubjectBtn" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
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
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse($departments as $department)
                        <tr>
                            <td class="py-3 px-4 border-b">{{ $department->code }}</td>
                            <td class="py-3 px-4 border-b">{{ $department->name }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="py-3 px-4 text-center text-gray-500">No subjects found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <!-- Add Subject Modal -->
    <div id="addSubjectModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded-lg w-96">
            <h3 class="text-lg font-semibold mb-4">Add New Department</h3>

            <!-- Add Subject Form -->
            <form id="addSubjectForm" method="POST" action="{{ route('department.store') }}">
                @csrf
                <div class="mb-4">
                    <label for="code" class="block text-gray-700">Department Code</label>
                    <input type="text" name="code" id="subjectID" required class="w-full px-3 py-2 border rounded-lg">
                </div>
                <div class="mb-4">
                    <label for="name" class="block text-gray-700">Department Name</label>
                    <input type="text" name="name" id="subjectName" required
                        class="w-full px-3 py-2 border rounded-lg">
                </div>
                <div class="flex justify-end">
                    <button type="button" id="cancelBtn"
                        class="px-4 py-2 mr-2 text-gray-500 hover:text-gray-700">Cancel</button>
                    <button type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Save</button>
                </div>
            </form>
        </div>
    </div>
    <!-- JavaScript for Sidebar Toggle -->
    <script>
        //Toggle modal for add subject
        document.getElementById('addSubjectBtn').addEventListener('click', function() {
            document.getElementById('addSubjectModal').classList.remove('hidden');
        });

        document.getElementById('cancelBtn').addEventListener('click', function() {
            document.getElementById('addSubjectModal').classList.add('hidden');
        });
    </script>
@endsection
