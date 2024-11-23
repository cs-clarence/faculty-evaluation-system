<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseSemester;
use App\Models\CourseSubject;
use App\Models\Department;
use App\Models\Subject;
use Illuminate\Http\Request;
use \DB;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subjects = Subject::all();
        $courses = Course::with('department')->get();
        $departments = Department::all();
        return view('admin.courses.index', compact('subjects', 'courses', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'code' => ['required', 'string', 'max:255', 'unique:courses,code'],
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
        ]);

        // Step 1: Create a new course
        $course = new Course();
        $course->code = $validatedData['code'];
        $course->name = $validatedData['name'];
        $course->department_id = $validatedData['department_id'];
        $course->save();

        return redirect()->route('courses.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    //department controller
    public function department_index()
    {

        $departments = Department::all();
        return view('admin.departments.index', compact('departments'));
    }

    public function department_store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'code' => 'required|unique:department,code',
            'name' => 'required',
        ]);

        // Create a new subject
        Department::create([
            'code' => $request->code,
            'name' => $request->name,
        ]);

        // Redirect back with a success message
        return redirect()->route('departments.index')->with('success', 'Subject added successfully!');
    }

    public function storeSemester(Request $request, Course $course)
    {
        $request->validate([
            'year_level' => 'required|string|max:255',
            'semester' => 'required|string|max:255',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'exists:subjects,id',
        ]);

        DB::transaction(function () use ($course, $request) {
            // Store the new semester for the course
            $semester = new CourseSemester([
                'course_id' => $course->id,
                'year_level' => $request->year_level,
                'semester' => $request->semester,
            ]);
            $semester->save();

            // Attach selected subjects to the semester
            $courseSubjects = [];
            foreach ($request->subjects as $subjectId) {
                $courseSubjects[] = new CourseSubject([
                    'course_semester_id' => $semester->id,
                    'subject_id' => $subjectId,
                ]);
            }

            $semester->courseSubjects()->saveMany($courseSubjects);
        });

        return redirect()->route('courses.showSemesters', $course->id)
            ->with('success', 'Semester added successfully');
    }

    // Show the course semesters page for a specific course
    public function showSemesters(Course $course)
    {
        // Get all the semesters associated with the course
        $course_semesters = $course->course_semesters;
        $subjects = Subject::all();

        return view('admin.courses.semesters.index', compact('course', 'course_semesters', 'subjects'));
    }
}
