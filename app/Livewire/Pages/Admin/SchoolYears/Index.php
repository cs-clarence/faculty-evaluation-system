<?php

namespace App\Livewire\Pages\Admin\SchoolYears;

use App\Http\Requests\StoreSchoolYearRequest;
use App\Models\SchoolYear;
use Livewire\Component;

class Index extends Component
{
    public bool $addModalOpen = false;

    public function render()
    {
        $schoolYears = SchoolYear::all();
        return view('livewire.pages.admin.school-years.index')
            ->with(compact('schoolYears'))
            ->layout('components.layouts.admin');
    }

    public function openAddModal()
    {
        $this->addModalOpen = true;
    }

    public function store(StoreSchoolYearRequest $request)
    {
        $request->validated();

    }

    public function closeAddModal()
    {
        $this->addModalOpen = false;
    }
}
