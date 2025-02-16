<?php
namespace App\Livewire\Components\Forms;

use App\Livewire\Forms\FormQuestionForm;
use App\Livewire\Forms\FormQuestionOptionForm;
use App\Livewire\Forms\FormSectionForm;
use App\Models\Form;
use App\Models\FormQuestion;
use App\Models\FormQuestionOption;
use App\Models\FormQuestionType;
use App\Models\FormSection;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Builder extends Component
{
    public Form $form;

    public bool $sectionFormIsOpen  = false;
    public bool $questionFormIsOpen = false;
    public bool $optionFormIsOpen   = false;

    public FormSectionForm $sectionForm;
    public FormQuestionForm $questionForm;
    public FormQuestionOptionForm $optionForm;

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
        $this->sectionForm->reset();
    }

    public function saveSection()
    {
        $this->sectionForm->form_id = $this->form->id;
        $this->sectionForm->submit();
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

    public function openQuestionForm(FormSection $section, ?FormQuestion $model)
    {
        if (isset($model)) {
            $this->questionForm->set($model);
        }

        $this->questionForm->form_id         = $this->form->id;
        $this->questionForm->form_section_id = $section->id;
        $this->questionFormIsOpen            = true;
    }

    public function closeQuestionForm()
    {
        $this->questionForm->reset();
        $this->questionFormIsOpen = false;
    }

    public function saveQuestion()
    {
        $this->questionForm->form_id = $this->form->id;
        $this->questionForm->submit();
        $this->closeQuestionForm();
    }

    public function updateQuestionType(FormQuestion $model, string $type)
    {
        $valid = validator(['type' => $type], [
            'type' => Rule::enum(FormQuestionType::class),
        ])->validate();
        $model->update($valid);
    }

    public function openOptionForm(FormQuestion $question, ?FormQuestionOption $model)
    {
        if (isset($model)) {
            $this->optionForm->set($model);
        }

        $this->optionForm->form_question_id = $question->id;
        $this->optionFormIsOpen             = true;
    }

    public function closeOptionForm()
    {
        $this->optionFormIsOpen = false;
        $this->optionForm->reset();
    }

    public function saveOption()
    {
        $this->optionForm->submit();
        $this->closeOptionForm();
    }
}
