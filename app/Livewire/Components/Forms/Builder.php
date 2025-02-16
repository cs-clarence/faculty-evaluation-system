<?php
namespace App\Livewire\Components\Forms;

use App\Livewire\Forms\FormSectionForm;
use App\Models\Form;
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
}
