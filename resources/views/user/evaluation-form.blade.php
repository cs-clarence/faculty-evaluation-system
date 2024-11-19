<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-black leading-tight">
            {{ __('Evaluation Form') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="">
                        @csrf

                        <div>

                        </div>

                        <h3 class="text-lg font-semibold mb-4">Please rate the following:</h3>
                        
                        <!-- Likert Questions -->
                        <div class="p-3 flex justify-center font-serif">
                            <label>1 - Strongly Disagree 2 - Disagree 3 - Neutral 4 - Agree 5 - Strongly Agree</label>
                        </div>

                        <!-- Box 1 -->
                        <div class="p-4 rounded-lg m-2 border-2 border-gray-800 shadow-md">
                            <div class="p-3 flex justify-center font-serif">
                                <label>TEACHER'S PERSONALITY,PROMPTNESS AND PREPAREDNESS</label>
                            </div>

                                <!-- Question 1 -->
                                <div class="mb-6">
                                    <label class="block font-medium text-gray-700 mb-2">1. The teacher/instructor is punctual on starting online meetings and in postings of assignments or work tasks.</label>
                                    <div class="flex space-x-4">
                                        @foreach (range(1, 5) as $value)
                                            <label class="flex items-center space-x-2">
                                                <input type="radio" name="box1_question1" value="{{ $value }}" required>
                                                <span>{{ $value }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Question 2 -->
                                <div class="mb-6">
                                    <label class="block font-medium text-gray-700 mb-2">2. The teacher/instructor demonstrated enthusiasm and knowledge during discussions of topics in synchronous meetings.</label>
                                    <div class="flex space-x-4">
                                        @foreach (range(1, 5) as $value)
                                            <label class="flex items-center space-x-2">
                                                <input type="radio" name="box1_question2" value="{{ $value }}" required>
                                                <span>{{ $value }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Question 3 -->
                                <div class="mb-6">
                                    <label class="block font-medium text-gray-700 mb-2">3. The teacher/instructor is effective and creative in giving examples and sharing presentations during online discussions.</label>
                                    <div class="flex space-x-4">
                                        @foreach (range(1, 5) as $value)
                                            <label class="flex items-center space-x-2">
                                                <input type="radio" name="box1_question3" value="{{ $value }}" required>
                                                <span>{{ $value }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Question 4 -->
                                <div class="mb-6">
                                    <label class="block font-medium text-gray-700 mb-2">4. The teacher/instructor provides encouragement to students to participate and accomplish tasks on time.</label>
                                    <div class="flex space-x-4">
                                        @foreach (range(1, 5) as $value)
                                            <label class="flex items-center space-x-2">
                                                <input type="radio" name="box1_question4" value="{{ $value }}" required>
                                                <span>{{ $value }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Question 5 -->
                                <div class="mb-6">
                                    <label class="block font-medium text-gray-700 mb-2">5. Dresses Neatly and Appropriately.</label>
                                    <div class="flex space-x-4">
                                        @foreach (range(1, 5) as $value)
                                            <label class="flex items-center space-x-2">
                                                <input type="radio" name="box1_question5" value="{{ $value }}" required>
                                                <span>{{ $value }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                        </div>

                        <!-- Box 2 -->
                        <div class="p-4 rounded-lg m-2 border-2 border-gray-800 shadow-md">
                            <div class="p-3 flex justify-center font-serif">
                                <label>COMMUNICATION AND INTERACTION</label>
                            </div>

                                <!-- Question 1 -->
                                <div class="mb-6">
                                    <label class="block font-medium text-gray-700 mb-2">1. The teacher/instructor promotes mutual respect among students.</label>
                                    <div class="flex space-x-4">
                                        @foreach (range(1, 5) as $value)
                                            <label class="flex items-center space-x-2">
                                                <input type="radio" name="box2_question1" value="{{ $value }}" required>
                                                <span>{{ $value }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Question 2 -->
                                <div class="mb-6">
                                    <label class="block font-medium text-gray-700 mb-2">2. The teacher/instructor responds constructively to student questions, opinions and other inputs.</label>
                                    <div class="flex space-x-4">
                                        @foreach (range(1, 5) as $value)
                                            <label class="flex items-center space-x-2">
                                                <input type="radio" name="box2_question2" value="{{ $value }}" required>
                                                <span>{{ $value }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Question 3 -->
                                <div class="mb-6">
                                    <label class="block font-medium text-gray-700 mb-2">3. The teacher/instructor encourages interaction among all the members of the class.</label>
                                    <div class="flex space-x-4">
                                        @foreach (range(1, 5) as $value)
                                            <label class="flex items-center space-x-2">
                                                <input type="radio" name="box2_question3" value="{{ $value }}" required>
                                                <span>{{ $value }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Question 4 -->
                                <div class="mb-6">
                                    <label class="block font-medium text-gray-700 mb-2">4. The teacher/instructor promptly and effectively handles inappropriate discussion postings or other unacceptable online behaviors.</label>
                                    <div class="flex space-x-4">
                                        @foreach (range(1, 5) as $value)
                                            <label class="flex items-center space-x-2">
                                                <input type="radio" name="box2_question4" value="{{ $value }}" required>
                                                <span>{{ $value }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                        </div>

                        <!-- END BOX -->

                        <!-- Box 3 -->
                        <div class="p-4 rounded-lg m-2 border-2 border-gray-800 shadow-md">
                            <div class="p-3 flex justify-center font-serif">
                                <label>COURSE DESIGN AND CONTENT</label>
                            </div>

                                <!-- Question 1 -->
                                <div class="mb-6">
                                    <label class="block font-medium text-gray-700 mb-2">1. The teacher/instructor demonstrates appropriate expertise and knowledge of the course subject.</label>
                                    <div class="flex space-x-4">
                                        @foreach (range(1, 5) as $value)
                                            <label class="flex items-center space-x-2">
                                                <input type="radio" name="box3_question1" value="{{ $value }}" required>
                                                <span>{{ $value }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Question 2 -->
                                <div class="mb-6">
                                    <label class="block font-medium text-gray-700 mb-2">2. The teacher/instructor provides content that is appropriate and relevant to the subject.</label>
                                    <div class="flex space-x-4">
                                        @foreach (range(1, 5) as $value)
                                            <label class="flex items-center space-x-2">
                                                <input type="radio" name="box3_question2" value="{{ $value }}" required>
                                                <span>{{ $value }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Question 3 -->
                                <div class="mb-6">
                                    <label class="block font-medium text-gray-700 mb-2">3. The teacher/instructor explains difficult terms, concepts or problems about the topic in various ways and in levels that every student can understand.</label>
                                    <div class="flex space-x-4">
                                        @foreach (range(1, 5) as $value)
                                            <label class="flex items-center space-x-2">
                                                <input type="radio" name="box3_question3" value="{{ $value }}" required>
                                                <span>{{ $value }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Question 4 -->
                                <div class="mb-6">
                                    <label class="block font-medium text-gray-700 mb-2">4. The teacher/instructor relates coursework to course content.</label>
                                    <div class="flex space-x-4">
                                        @foreach (range(1, 5) as $value)
                                            <label class="flex items-center space-x-2">
                                                <input type="radio" name="box3_question4" value="{{ $value }}" required>
                                                <span>{{ $value }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Question 5 -->
                                <div class="mb-6">
                                    <label class="block font-medium text-gray-700 mb-2">5. The online subject/course clearly articulates course policies and content.</label>
                                    <div class="flex space-x-4">
                                        @foreach (range(1, 5) as $value)
                                            <label class="flex items-center space-x-2">
                                                <input type="radio" name="box3_question5" value="{{ $value }}" required>
                                                <span>{{ $value }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Question 6 -->
                                <div class="mb-6">
                                    <label class="block font-medium text-gray-700 mb-2">6. The course subject utilizes a variety of online resources and tools to facilitate student comprehension and engagement in learning the course.</label>
                                    <div class="flex space-x-4">
                                        @foreach (range(1, 5) as $value)
                                            <label class="flex items-center space-x-2">
                                                <input type="radio" name="box3_question6" value="{{ $value }}" required>
                                                <span>{{ $value }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Question 7 -->
                                <div class="mb-6">
                                    <label class="block font-medium text-gray-700 mb-2">7. The teacher/instructor provides an online syllabus that details the terms of class interaction for both teacher and students, with clear expectations and grading criteria.</label>
                                    <div class="flex space-x-4">
                                        @foreach (range(1, 5) as $value)
                                            <label class="flex items-center space-x-2">
                                                <input type="radio" name="box3_question7" value="{{ $value }}" required>
                                                <span>{{ $value }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                        </div>

                        <!-- END BOX -->

                        <!-- Box 4 -->
                        <div class="p-4 rounded-lg m-2 border-2 border-gray-800 shadow-md">
                            <div class="p-3 flex justify-center font-serif">
                                <label>CLASSROOM MANAGEMENT SKILLS</label>
                            </div>

                                <!-- Question 1 -->
                                <div class="mb-6">
                                    <label class="block font-medium text-gray-700 mb-2">1. The teacher/instructor demonstrates the skills and ability to effectively use the prescribed platforms and applications for online learning.</label>
                                    <div class="flex space-x-4">
                                        @foreach (range(1, 5) as $value)
                                            <label class="flex items-center space-x-2">
                                                <input type="radio" name="box4_question1" value="{{ $value }}" required>
                                                <span>{{ $value }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Question 2 -->
                                <div class="mb-6">
                                    <label class="block font-medium text-gray-700 mb-2">2. The teacher/instructor incorporates relevant multimedia and other visual resources into the online module.</label>
                                    <div class="flex space-x-4">
                                        @foreach (range(1, 5) as $value)
                                            <label class="flex items-center space-x-2">
                                                <input type="radio" name="box4_question2" value="{{ $value }}" required>
                                                <span>{{ $value }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Question 3 -->
                                <div class="mb-6">
                                    <label class="block font-medium text-gray-700 mb-2">3. The teacher/instructor utilizes synchronous and asynchronous tools (e.g., discussion boards, chat tools, file and link sharing) effectively.</label>
                                    <div class="flex space-x-4">
                                        @foreach (range(1, 5) as $value)
                                            <label class="flex items-center space-x-2">
                                                <input type="radio" name="box4_question3" value="{{ $value }}" required>
                                                <span>{{ $value }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Question 4 -->
                                <div class="mb-6">
                                    <label class="block font-medium text-gray-700 mb-2">4. The teacher/instructor provides student-centered lessons and activities that are based on concepts of active learning and that are connected to real-world applications.</label>
                                    <div class="flex space-x-4">
                                        @foreach (range(1, 5) as $value)
                                            <label class="flex items-center space-x-2">
                                                <input type="radio" name="box4_question4" value="{{ $value }}" required>
                                                <span>{{ $value }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                        </div>

                        <!-- END BOX -->

                        
                        
                        <!-- Submit Button -->
                        <div>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
