<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Course;
use App\Models\CourseSemester;
use App\Models\Department;
use Illuminate\Support\Facades\Log;

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
        return view('/admin/course', compact('subjects','courses','departments'));
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
            'department_id' => 'required|exists:departments,id'
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
    public function department_index(){

        $departments = Department::all();
        return view ('/admin/department', compact('departments'));
    }

    public function department_store(Request $request){
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

    public function course_semester_store(Request $request){

        $validatedData = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'year_level' => 'required|string|max:255',
            'semester' => 'required|string|max:255',    
        ]);

        // Step 1: Create a new course
        $course_semester = new CourseSemester();
        $course_semester->year_level = $validatedData['year_level'];
        $course_semester->semester = $validatedData['semester'];
        $course_semester->course_id = $validatedData['course_id'];
        $course_semester->save();


        return redirect()->route('course');
    }
}
