<?php

namespace App\Services;

use App\Models\SchoolYear;
use App\Models\Semester;

class SchoolYearService
{
    public function create(int $yearStart, int $semesters = 3)
    {
        $sy = SchoolYear::factory()->yearStart($yearStart)->createOne();

        $semesters = self::createNSemesters($sy, $semesters);

        $sy->semesters()->saveMany($semesters);

        return $sy;
    }

    public function createMissingStartingFromYear(int $yearStart, int $expectedYears = 4, int $semestersPerYear = 3)
    {
        $upTo = $yearStart + $expectedYears;
        for ($i = $yearStart; $i <= $upTo; ++$i) {
            if (SchoolYear::whereYearStart($i)->exists()) {
                $sy = SchoolYear::whereYearStart($i)->first();
                $this->update($sy, $i, $semestersPerYear);
            } else {
                $this->create($i, $semestersPerYear);
            }
        }
    }

    public function update(SchoolYear | int $schoolYear, int $yearStart, int $semesters)
    {
        $sy = $schoolYear instanceof SchoolYear ? $schoolYear : SchoolYear::whereId($schoolYear)->first();

        $existingSemestersCount = isset($sy->semesters_count) ? $sy->semesters_count : $sy->semesters()->count();

        $yearEnd = $yearStart + 1;
        if ($yearStart !== $sy->year_start || $yearEnd !== $sy->year_end) {
            $sy->update([
                'year_start' => $yearStart,
                'year_end' => $yearEnd,
            ]);
        }

        if ($semesters > $existingSemestersCount) {
            $sy->save();
            $semesters = self::createNSemesters($sy, $semesters);
            $sy->semesters()->saveMany($semesters);
        }

        return $sy;

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
