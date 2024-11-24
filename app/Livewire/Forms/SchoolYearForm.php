<?php

namespace App\Livewire\Forms;

use App\Models\SchoolYear;
use App\Models\Semester;
use Livewire\Attributes\Locked;
use Livewire\Form;
use \DB;

class SchoolYearForm extends Form
{
    #[Locked]
    public ?int $id = null;
    public ?int $year_start = null;
    public ?int $year_end = null;
    public ?int $semesters = null;

    public function rules()
    {

        return [
            'id' => 'nullable|integer|exists:school_years,id',
            'year_start' => 'required|numeric|gte:2000|lt:year_end',
            'year_end' => ['required', 'numeric', 'gte:2000', 'gt:year_start', 'lte:' . $this->year_start + 1],
            'semesters' => ['required', 'numeric', 'gt:0', 'lt:4'],
        ];
    }

    public function save()
    {
        $this->validate();
        if (isset($this->id)) {
            DB::transaction(function () {
                $sy = SchoolYear::whereId($this->id)->first();
                $currentSemesters = $sy->semesters()->count();

                $this->validate([
                    'semesters' => "gte:$currentSemesters",
                ]);

                $sy->update([
                    'year_start' => $this->year_start,
                    'year_end' => $this->year_end,
                ]);

                $sy->save();

                $semesters = self::createNSemesters($sy, $this->semesters);

                $sy->semesters()->saveMany($semesters);
            });

        } else {
            DB::transaction(function () {
                $sy = new SchoolYear([
                    'year_start' => $this->year_start,
                    'year_end' => $this->year_end,
                ]);

                $sy->save();

                $semesters = self::createNSemesters($sy, $this->semesters);

                $sy->semesters()->saveMany($semesters);
            });
        }
    }

    public function set(SchoolYear $sy)
    {
        $this->fill(
            [
                 ...$sy->attributesToArray(),
                'semesters' => $sy->semesters()->count()]
        );
    }

    public function clear()
    {
        $this->reset();
        $this->resetErrorBag();
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
