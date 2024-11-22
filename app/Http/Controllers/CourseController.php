<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseSemester;
use App\Models\Department;
use App\Models\Subject;
use Illuminate\Http\Request;

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
        return view('/admin/course', compact('subjects', 'courses', 'departments'));
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
            'course_code' => 'required|string|max:255',
            'course_name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
        ]);

        // Step 1: Create a new course
        $course = new Course();
        $course->course_code = $validatedData['course_code'];
        $course->course_name = $validatedData['course_name'];
        $course->department_id = $validatedData['department_id'];
        $course->save();

        return redirect()->route('course');
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
        return view('/admin/department', compact('departments'));
    }

    public function department_store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'department_code' => 'required|unique:departments,department_code',
            'department_name' => 'required',
        ]);

        // Create a new subject
        Department::create([
            'department_code' => $request->department_code,
            'department_name' => $request->department_name,
        ]);

        // Redirect back with a success message
        return redirect()->route('department')->with('success', 'Subject added successfully!');
    }

    public function storeSemester(Request $request, Course $course)
    {
        $request->validate([
            'year_level' => 'required|string|max:255',
            'semester' => 'required|string|max:255',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'exists:subjects,id',
        ]);

        // Store the new semester for the course
        $semester = CourseSemester::create([
            'course_id' => $course->id,
            'year_level' => $request->year_level,
            'semester' => $request->semester,
        ]);

        // Attach selected subjects to the semester
        $semester->subjects()->sync($request->subjects);

        return redirect()->route('courses.showSemesters', $course->id)
            ->with('success', 'Semester added successfully');
    }

    // Show the course semesters page for a specific course
    public function showSemesters(Course $course)
    {
        // Get all the semesters associated with the course
        $course_semesters = $course->course_semesters;
        $subjects = Subject::all();

        return view('/admin/semester', compact('course', 'course_semesters', 'subjects'));
    }
}
