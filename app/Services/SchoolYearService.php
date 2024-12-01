<?php

namespace App\Services;

use App\Models\SchoolYear;
use App\Models\Semester;

class SchoolYearService
{
    public function create(int $yearStart, int $semesters)
    {
        $sy = SchoolYear::factory()->yearStart($yearStart)->createOne();

        $semesters = self::createNSemesters($sy, $semesters);

        $sy->semesters()->saveMany($semesters);

        return $sy;
    }

    public function update(SchoolYear | int $schoolYear, int $yearStart, int $semesters)
    {
        $sy = $schoolYear instanceof SchoolYear ? $schoolYear : SchoolYear::whereId($schoolYear)->first();

        $sy->update([
            'year_start' => $yearStart,
            'year_end' => $yearStart + 1,
        ]);

        $sy->save();

        $semesters = self::createNSemesters($sy, $semesters);

        $sy->semesters()->saveMany($semesters);
    }

    private static function createNSemesters(SchoolYear $sy, int $semesters)
    {
        $semestersArr = [];
        $count = $sy->semesters()->count();

        $start = $count + 1;
        for ($i = $start; $i <= $semesters; $i++) {
            $semestersArr[] = new Semester([
                'school_year_id' => $sy->id,
                'semester' => $i,
            ]);
        }

        return $semestersArr;
    }
}
