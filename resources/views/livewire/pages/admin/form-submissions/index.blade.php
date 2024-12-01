<div class="contents">
    <div class="top flex justify-start mb-4">
        <h2 class="text-2xl font-bold">Form Submissions</h2>
    </div>
    <div class="main-dash grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="col-span-1 md:col-span-3 overflow-auto">
            <table class="min-w-full bg-white rounded-lg overflow-hidden shadow-md">
                <thead class="bg-gray-200 text-gray-600">
                    <tr>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Student</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Teacher</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Subject</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Rating</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse($formSubmissions as $i)
                        <tr>
                            <td class="py-3 px-4 border-b">
                                {{ $i->studentSubject->studentSemester->student->user->name }}</td>
                            <td class="py-3 px-4 border-b">{{ $i->teacher->user->name }}</td>
                            <td class="py-3 px-4 border-b">
                                {{ $i->studentSubject->subject_name }} ({{ $i->studentSubject->subject_code }})</td>
                            <td class="py-3 px-4 border-b">{{ $i->rating }}%</td>
                            <td class="py-3 px-4 border-b">
                                <a href="{{ route('admin.form-submissions.form-submission', ['formSubmission' => $i->id]) }}"
                                    wire:navigate
                                    class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-3 px-4 text-center text-gray-500">No Form Submissions found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
