<?php

namespace App\Livewire\Forms;

use App\Models\SchoolYear;
use App\Models\Semester;
use Livewire\Attributes\Locked;
use Livewire\Form;
use \DB;

class SchoolYearForm extends Form
{
    public bool $isOpen = false;
    #[Locked]
    public ?int $schoolYearId = null;
    public ?int $yearStart = null;
    public ?int $yearEnd = null;
    public ?int $semesters = null;

    public function rules()
    {
        return [
            'schoolYearId' => 'nullable|integer|exists:school_years,id',
            'yearStart' => 'required|numeric|gte:2000|lt:yearEnd',
            'yearEnd' => ['required', 'numeric', 'gte:2000', 'gt:yearStart'],
            'semesters' => ['required', 'numeric', 'gt:0', 'lt:4'],
        ];
    }

    public function save()
    {
        $this->validate();
        if (isset($this->schoolYearId)) {
            $result = DB::transaction(function () {
                $sy = SchoolYear::whereId($this->schoolYearId)->first();
                $currentSemesters = $sy->semesters()->count();

                if ($this->semesters < $currentSemesters) {
                    $this->addError('semesters', 'The semester count be the same or more than the previous');
                    return false;
                }

                $sy->update($this->all());

                $sy->save();

                $semesters = self::createNSemesters($sy, $this->semesters);

                $sy->semesters()->saveMany($semesters);

                return true;
            });

            if (!$result) {
                return;
            }
        } else {
            DB::transaction(function () {
                $sy = new SchoolYear([
                    'year_start' => $this->yearStart,
                    'year_end' => $this->yearEnd,
                ]);

                $sy->save();

                $semesters = self::createNSemesters($sy, $this->semesters);

                $sy->semesters()->saveMany($semesters);
            });
        }
        $this->isOpen = false;
        $this->reset();
    }

    public function open(?SchoolYear $sy = null)
    {
        if (isset($sy)) {
            $this->set($sy);
        }
        $this->isOpen = true;
    }

    public function set(SchoolYear $sy)
    {
        $this->fill(
            ['schoolYearId' => $sy->id,
                'yearStart' => $sy->year_start,
                'yearEnd' => $sy->year_end,
                'semesters' => $sy->semesters()->count()]
        );
    }

    public function close()
    {
        $this->isOpen = false;
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
