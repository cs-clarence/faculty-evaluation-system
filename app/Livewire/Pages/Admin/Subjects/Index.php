<?php

namespace App\Livewire\Pages\Admin\Subjects;

use App\Livewire\Forms\SubjectForm;
use App\Models\Subject;
use Livewire\Component;

class Index extends Component
{
    public SubjectForm $form;
    public function render()
    {
        $subjects = Subject::all();
        return view('livewire.pages.admin.subjects.index')
            ->with(compact('subjects'))
            ->layout('components.layouts.admin');
    }

    public function openForm()
    {
        $this->form->open();
    }

    public function closeForm()
    {
        $this->form->close();
    }

    public function edit(Subject $subject)
    {
        $this->form->open($subject);
    }

    public function save()
    {
        $this->form->save();
    }

    public function archive(Subject $subject)
    {
        $subject->archive();
    }

    public function delete(Subject $subject)
    {
        $subject->delete();
    }

    public function unarchive(Subject $subject)
    {
        $subject->unarchive();
    }

}
