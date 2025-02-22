<?php
namespace App\Livewire\Pages\Admin\Subjects;

use App\Livewire\Forms\SubjectForm;
use App\Livewire\Traits\WithSearch;
use App\Models\Subject;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination, WithSearch;
    public SubjectForm $form;
    public ?Subject $subject;
    public bool $isFormOpen = false;

    public function render()
    {
        $subjects = Subject::withCount(['courseSemesters'])
            ->orderBy('code')
            ->orderBy('name')
            ->orderBy('created_at')
            ->orderBy('updated_at');

        if ($this->shouldSearch()) {
            $subjects = $subjects->fullTextSearch([
                'columns' => ['name', 'code'],
            ], $this->searchText);
        }

        $subjects = $subjects->cursorPaginate(15);

        return view('livewire.pages.admin.subjects.index')
            ->with(compact('subjects'))
            ->layout('components.layouts.admin');
    }

    public function openForm()
    {
        $this->isFormOpen = true;
    }

    public function closeForm()
    {
        $this->isFormOpen = false;
        $this->form->clear();
        unset($this->subject);
    }

    public function edit(Subject $subject)
    {
        $this->subject    = $subject;
        $this->isFormOpen = true;
        $this->form->set($subject);
    }

    public function save()
    {
        $this->form->submit();
        $this->closeForm();
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
