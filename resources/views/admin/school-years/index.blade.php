@extends('layouts.admin')


@section('content')
    <div class="top flex justify-end mb-4">
        <button id="addButton" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
            Add School Year
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
                        <th class="py-3 px-4 text-left text-sm font-semibold">School Year</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Semesters</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse($schoolYears as $schoolYear)
                        <tr>
                            <td class="py-3 px-4 border-b">
                                {{ $schoolYear->year_start }}
                                - {{ $schoolYear->year_end }}
                            </td>
                            <td class="py-3 px-4 border-b">{{ $schoolYear->semesters()->count() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="py-3 px-4 text-center text-gray-500">
                                No school years found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div id="addModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded-lg w-96">
            <h3 class="text-lg font-semibold mb-4">Add New School Year</h3>
            <form id="addForm" method="POST" action="{{ route('school-years.store') }}">
                @csrf
                <div class="mb-4">
                    <label for="year_start" class="block text-gray-700">Year Start</label>
                    <input type="text" name="year_start" id="year_start" required type="number"
                        class="w-full px-3 py-2 border rounded-lg" value="{{ old('year_start') }}">
                    @error('year_start')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="year_end" class="block text-gray-700">Year End</label>
                    <input type="text" name="year_end" id="year_end" required class="w-full px-3 py-2 border rounded-lg"
                        type="number" value="{{ old('year_end') }}">
                    @error('year_end')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="semesters" class="block text-gray-700">Semesters</label>
                    <input type="text" name="semesters" id="semesters" required type="number"
                        class="w-full px-3 py-2 border rounded-lg" value="{{ old('semesters') }}">
                    @error('semesters')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex justify-end">
                    <button type="button" id="cancelButton"
                        class="px-4 py-2 mr-2 text-gray-500 hover:text-gray-700">Cancel</button>
                    <button type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Save</button>
                </div>
                <script>
                    function hideModal() {
                        const addModal = document.getElementById('addModal')

                        if (addModal) {
                            addModal.classList.add('hidden');
                        }
                    }
                    document.getElementById('cancelButton').addEventListener('click', hideModal);
                </script>
            </form>
        </div>
    </div>

    <script>
        const addButton = document.getElementById('addButton');
        addButton.addEventListener('click', function() {
            document.getElementById('addModal').classList.remove('hidden');
        });
    </script>
@endsection
