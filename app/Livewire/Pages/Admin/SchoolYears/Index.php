<?php
namespace App\Livewire\Pages\Admin\SchoolYears;

use App\Livewire\Forms\SchoolYearForm;
use App\Models\SchoolYear;
use Livewire\Component;

class Index extends Component
{
    public SchoolYearForm $form;
    public bool $isFormOpen        = false;
    public ?SchoolYear $schoolYear = null;

    public function mount()
    {
    }

    public function render()
    {
        $schoolYears = SchoolYear::withCount(['semesters'])
            ->orderBy('year_start')
            ->orderBy('year_end');

        return view('livewire.pages.admin.school-years.index')
            ->with(compact('schoolYears'))
            ->layout('components.layouts.admin');
    }

    public function openForm()
    {
        $this->isFormOpen = true;
        if (! isset($this->form->semesters)) {
            $this->form->semesters = 2;
        }
    }

    public function save()
    {
        $this->form->submit();
        $this->form->clear();
        $this->isFormOpen = false;
    }

    public function closeForm()
    {
        $this->form->clear();
        $this->isFormOpen = false;
        $this->schoolYear = null;
    }

    public function edit(SchoolYear $sy)
    {
        $this->schoolYear = $sy;
        $this->form->set($sy);
        $this->openForm();
    }

    public function delete(SchoolYear $sy)
    {
        $sy->delete();
    }

    public function updated(string $name, $value)
    {
        if ($name === 'form.year_start') {
            $this->form->year_end = $value + 1;
        }
    }
}
