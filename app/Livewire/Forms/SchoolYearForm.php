<?php

namespace App\Livewire\Forms;

use App\Facades\Services\SchoolYearService;
use App\Models\SchoolYear;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use \DB;

class SchoolYearForm extends BaseForm
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
            'year_start' => ['required', 'numeric', 'gte:2000', 'lt:year_end',
                Rule::unique('school_years', 'year_start')
                    ->where('year_end', $this->year_end)
                    ->ignore($this->id, 'id'),
            ],

            'year_end' => ['required', 'numeric', 'gte:2000', 'gt:year_start', 'lte:' . $this->year_start + 1],

            'semesters' => ['required', 'numeric', 'gt:0', 'lt:4'],
        ];
    }

    public function submit()
    {
        $this->validate();
        if (isset($this->id)) {
            DB::transaction(function () {
                $sy = SchoolYear::whereId($this->id)->first();
                $currentSemesters = $sy->semesters()->count();

                $this->validate([
                    'semesters' => "gte:$currentSemesters",
                ]);
                SchoolYearService::update($sy, $this->year_start, $this->semesters);
            });

        } else {
            DB::transaction(function () {
                SchoolYearService::create($this->year_start, $this->semesters);
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
}
