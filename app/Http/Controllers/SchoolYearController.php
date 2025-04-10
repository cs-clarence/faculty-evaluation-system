<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSchoolYearRequest;
use App\Http\Requests\UpdateSchoolYearRequest;
use App\Models\SchoolYear;
use App\Models\Semester;
use Illuminate\Support\Facades\Response;
use \DB;

class SchoolYearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.school-years.index', [
            'schoolYears' => SchoolYear::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.school-years.form');
    }

    private static function createNSemesters(SchoolYear $sy, int $semesters)
    {
        $semestersArr = [];
        $max = $sy->semesters()->max('semester');

        $start = $max + 1;
        $to = $start + $semesters;
        for ($i = $start; $i <= $to; $i++) {
            $sem = $semestersArr[] = new Semester();

            $sem->school_year_id = $sy->id;
            $sem->semester = $i;
        }

        return $semestersArr;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSchoolYearRequest $request)
    {
        $valid = $request->validated();

        DB::transaction(function () use ($valid) {
            $sy = new SchoolYear([
                'year_start' => $valid['year_start'],
                'year_end' => $valid['year_end'],
            ]);

            $sy->save();

            $semesters = self::createNSemesters($sy, $valid['semesters']);

            $sy->semesters()->saveMany($semesters);
        });

        return redirect()->route('school-years.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(SchoolYear $schoolYear)
    {
        return view('admin.school-years.show', [
            'schoolYear' => $schoolYear,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SchoolYear $schoolYear)
    {
        return view('admin.school-years.edit', [
            'schoolYear' => $schoolYear,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSchoolYearRequest $request, SchoolYear $schoolYear)
    {
        $valid = $request->validated();

        $additional = $valid->additional_semesters;

        $newSemesters = self::createNSemesters($schoolYear, $additional);

        $schoolYear->semesters()->saveMany($newSemesters);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SchoolYear $schoolYear)
    {
        $schoolYear->delete();
        return Response::redirectToRoute('school-years.index');
    }
}
