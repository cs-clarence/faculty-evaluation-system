<?php

namespace App\Livewire\Pages\Admin\SchoolYears;

use App\Livewire\Forms\SchoolYearForm;
use App\Models\SchoolYear;
use App\Models\Semester;
use Livewire\Component;

class Index extends Component
{
    public SchoolYearForm $form;

    public function mount()
    {
    }

    public function render()
    {
        $schoolYears = SchoolYear::all();
        return view('livewire.pages.admin.school-years.index')
            ->with(compact('schoolYears'))
            ->layout('components.layouts.admin');
    }

    public function openForm()
    {
        $this->form->open();
    }

    public function save()
    {
        $this->form->save();
    }

    public function closeForm()
    {
        $this->form->close();
    }

    public function edit(SchoolYear $sy)
    {
        $this->form->open($sy);
    }

    public function delete(SchoolYear $sy)
    {
        $sy->delete();
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
}
