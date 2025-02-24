<?php
namespace App\Livewire\Pages\AllRoles;

use App\Facades\Services\FileNameService;
use App\Facades\Services\FormSubmissionExportService;
use App\Livewire\Forms\FormSubmissionForm;
use App\Models\Form;
use App\Models\FormQuestion;
use App\Models\FormSubmission;
use App\Services\FormSubmissionExportOptions;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;
use Livewire\Component;

class ViewEvaluation extends Component
{
    public FormSubmissionForm $form;
    public Form $formModel;
    public FormSubmission $formSubmission;

    public function mount()
    {
        $this->formSubmission->load(['submissionPeriod.formSubmissionPeriodSemester']);
        $this->form->set($this->formSubmission);
        $this->formModel = Form::with(['sections.questions.options'])
            ->whereId($this->formSubmission->submissionPeriod->form_id)
            ->first();
    }

    public function render()
    {
        return view('livewire.pages.all-roles.view-evaluation', [
            'formSubmission' => $this->formSubmission,
            'formModel'      => $this->formModel,
            'options'        => self::getOptions(),
        ])
            ->layout('components.layouts.user');
    }

    public function getCreateWireModel()
    {
        return fn(FormQuestion $formQuestion) => 'form.questions.' . $formQuestion->id;
    }

    private function getFileName(string $ext)
    {
        $date = Carbon::now()->format('Y_m_d-H_i_s');
        // replace all non print characters to underscores
        $fileName = FileNameService::createName([
            $date,
            $this->formSubmission->submissionPeriod->form->name,
            $this->formSubmission->evaluatee->name,
        ], $ext);

        return $fileName;
    }

    private static function getOptions()
    {
        return new FormSubmissionExportOptions(
            showEvaluator: Gate::allows('viewEvaluator', FormSubmission::class)
        );
    }

    public function exportExcel()
    {
        $excel    = FormSubmissionExportService::getXlsx($this->formSubmission, self::getOptions());
        $fileName = $this->getFileName('xlsx');

        return Response::streamDownload(fn() => $excel->save('php://output'), $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function exportCsv()
    {
        $excel    = FormSubmissionExportService::getCsv($this->formSubmission, self::getOptions());
        $fileName = $this->getFileName('csv');

        return Response::streamDownload(fn() => $excel->save('php://output'), $fileName, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function exportPdf()
    {
        $pdf = FormSubmissionExportService::getPdf($this->formSubmission, self::getOptions());

        // replace all non print characters to underscores
        $fileName = $this->getFileName('pdf');
        return Response::streamDownload(fn() => print($pdf->output()), $fileName);
    }
}
