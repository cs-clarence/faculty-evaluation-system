<?php
namespace App\Livewire\Components\Forms;

use App\Livewire\Forms\FormSectionForm;
use App\Models\Form;
use App\Models\FormQuestion;
use App\Models\FormQuestionOption;
use App\Models\FormSection;
use Livewire\Component;

class Builder extends Component
{
    public Form $form;

    public bool $sectionFormIsOpen = false;

    public FormSectionForm $sectionForm;

    public function render()
    {
        return view('livewire.components.forms.builder')
            ->with([]);
    }

    public function openSectionForm(?FormSection $formSection)
    {
        if (isset($formSection)) {
            $this->sectionForm->set($formSection);
        }

        $this->sectionForm->form_id = $this->form->id;
        $this->sectionFormIsOpen    = true;
    }

    public function closeSectionForm()
    {
        $this->sectionFormIsOpen = false;
    }

    public function saveSection()
    {
        $this->sectionForm->form_id = $this->form->id;
        $this->sectionForm->submit();
        $this->sectionForm->clear();
        $this->closeSectionForm();
    }

    public function updateSectionTitle(FormSection $formSection, string $title)
    {
        $data = validator(['title' => $title], [
            'title' => ['required', 'min:0', 'max:255'],
        ])->validated();
        $formSection->update($data);
    }

    public function updateSectionDescription(FormSection $formSection, string $description)
    {
        $data = validator(['description' => $description], [
            'description' => ['nullable', 'string', 'max:1025'],
        ])->validated();

        $formSection->update($data);
    }

    public function updateQuestionTitle(FormQuestion $formQuestion, string $title)
    {
        $data = validator(['title' => $title], [
            'title' => ['required', 'min:0', 'max:255'],
        ])->validated();
        $formQuestion->update($data);
    }

    public function deleteSection(FormSection $model)
    {
        $model->delete();
    }

    public function moveBeforeSection(FormSection $model, FormSection $before)
    {
        $model->moveBefore($before);
    }

    public function moveAfterSection(FormSection $model, FormSection $after)
    {
        $model->moveAfter($after);
    }

    public function deleteQuestion(FormQuestion $model)
    {
        $model->delete();
    }

    public function moveBeforeQuestion(FormQuestion $model, FormQuestion $before)
    {
        $model->moveBefore($before);
    }

    public function moveAfterQuestion(FormQuestion $model, FormQuestion $after)
    {
        $model->moveAfter($after);
    }

    public function deleteOption(FormQuestionOption $model)
    {
        $model->delete();
    }

    public function moveBeforeOption(FormQuestionOption $model, FormQuestionOption $before)
    {
        $model->moveBefore($before);
    }

    public function moveAfterOption(FormQuestionOption $model, FormQuestionOption $after)
    {
        $model->moveAfter($after);
    }
}
