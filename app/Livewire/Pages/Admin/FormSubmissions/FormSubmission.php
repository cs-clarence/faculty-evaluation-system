<?php
namespace App\Livewire\Pages\Admin\FormSubmissions;

use App\Facades\Services\FileNameService;
use App\Livewire\Forms\FormSubmissionForm;
use App\Models\Form;
use App\Models\FormQuestion;
use App\Models\FormSubmission as Model;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class FormSubmission extends Component
{

    public Model $formSubmission;
    public Form $formModel;
    public FormSubmissionForm $form;

    public function getCreateWireModel()
    {
        return fn(FormQuestion $formQuestion) => 'form.questions.' . $formQuestion->id;
    }
    public function mount()
    {
        $this->formSubmission->load(['submissionPeriod.formSubmissionPeriodSemester', 'studentSubject']);
        $this->form->set($this->formSubmission);
        $this->formModel = Form::with(['sections.questions.options'])
            ->whereId($this->formSubmission->submissionPeriod->form_id)
            ->first();
    }

    public function render()
    {
        return view('livewire.pages.admin.form-submissions.form-submission')
            ->layout('components.layouts.admin');

    }

    private static function nextChar(string $char, int $increment = 1)
    {
        return chr(ord($char) + $increment);
    }

    private static function createSpreadsheet(Model $formSubmission, bool $showText = true, bool $showReason = true, bool $showInterpretation = true)
    {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $row         = 1;

        // Details

        $role = $formSubmission->evaluatee->role->display_name;

        $col = 'A';
        $sheet->setCellValue($col . $row, "Evaluatee {$role}");
        $col = self::nextChar($col);
        $sheet->setCellValue($col . $row, "Form");
        $col = self::nextChar($col);
        $sheet->setCellValue($col . $row, "Submission Period");
        $col = self::nextChar($col);
        if (isset($formSubmission->submissionPeriod->semester)) {
            $sheet->setCellValue($col . $row, "Semester");
            $col = self::nextChar($col);
        }

        $highestCol = $sheet->getHighestColumn();
        $sheet->getStyle('A1:' . $highestCol . '1')->getFont()->setBold(true);

        $row++;

        $col = 'A';
        $sheet->setCellValue($col . $row, $formSubmission->evaluatee->name);
        $col = self::nextChar($col);
        $sheet->setCellValue($col . $row, $formSubmission->submissionPeriod->form->name);
        $col = self::nextChar($col);
        $sheet->setCellValue($col . $row, $formSubmission->submissionPeriod->name);
        $col = self::nextChar($col);
        if (isset($formSubmission->submissionPeriod->semester)) {
            $sheet->setCellValue($col . $row, $formSubmission->submissionPeriod->semester);
            $col = self::nextChar($col);
        }

        $row += 2;

        $startTableRow = $row;

        // Headers
        $col = 'A';
        $sheet->setCellValue($col . $row, 'Question');
        $col = self::nextChar($col);
        if ($showText) {
            $sheet->setCellValue($col . $row, 'Answer');
            $col = self::nextChar($col);
        }
        if ($showInterpretation) {
            $sheet->setCellValue($col . $row, 'Interpretation');
            $col = self::nextChar($col);
        }
        $sheet->setCellValue($col . $row, 'Score')->mergeCells($col . $row . ':' . self::nextChar($col) . $row);
        $col = self::nextChar($col, 2);
        $sheet->setCellValue($col . $row, 'Weighted Score')->mergeCells($col . $row . ':' . self::nextChar($col) . $row);
        $col = self::nextChar($col, 2);
        if ($showReason) {
            $sheet->setCellValue($col . $row, 'Reason');
            $col = self::nextChar($col);
        }

        // Sub Headers
        $row++;
        $col = 'A';
        $col = $showText ? self::nextChar($col) : $col;
        $col = $showInterpretation ? self::nextChar($col) : $col;
        $col = self::nextChar($col);
        $sheet->setCellValue($col . $row, 'Value');
        $col = self::nextChar($col);
        $sheet->setCellValue($col . $row, 'Out Of');
        $col = self::nextChar($col);
        $sheet->setCellValue($col . $row, 'Value');
        $col = self::nextChar($col);
        $sheet->setCellValue($col . $row, 'Out Of');
        $col = self::nextChar($col);

        $highestCol = $sheet->getHighestColumn();
        $sheet->getStyle('A' . ($row - 1) . ':' . $highestCol . $row)->getFont()->setBold(true);

        // Data Rows
        $row++;
        foreach ($formSubmission->summary as $breakdown) {
            $col = 'A';
            $sheet->setCellValue($col . $row, $breakdown->question);
            $col++;
            if ($showText) {
                $sheet->setCellValue($col . $row, $breakdown->text);
                $col++;
            }
            if ($showInterpretation) {
                $sheet->setCellValue($col . $row, $breakdown->interpretation);
                $col++;
            }
            $sheet->setCellValue($col . $row, $breakdown->value);
            $col++;
            $sheet->setCellValue($col . $row, $breakdown->max_value);
            $col++;
            $sheet->setCellValue($col . $row, $breakdown->weighted_value);
            $col++;
            $sheet->setCellValue($col . $row, $breakdown->max_weighted_value);
            $col++;
            if ($showReason) {
                $sheet->setCellValue($col . $row, $breakdown->reason);
            }
            $row++;
        }

        // Footer Row
        $col = 'A';
        $sheet->setCellValue($col . $row, 'Total')->mergeCells($col . $row . ':' . chr(ord($col) + ($showText ? 1 : 0) + ($showInterpretation ? 1 : 0)) . $row);
        $sheet->getStyle($col . $row)->getAlignment()->setHorizontal('right');
        $col = chr(ord($col) + ($showText ? 1 : 0) + ($showInterpretation ? 1 : 0) + 1);
        $sheet->setCellValue($col . $row, $formSubmission->total_value);
        $col++;
        $sheet->setCellValue($col . $row, $formSubmission->form->total_max_value);
        $col++;
        $sheet->setCellValue($col . $row, $formSubmission->rating . '%');
        $col++;
        $sheet->setCellValue($col . $row, '100%');

        $highestCol = $sheet->getHighestColumn();
        $sheet->getStyle('A' . $row . ':' . $highestCol . $row)->getFont()->setBold(true);

        $endTableRow = $row;

        $highestCol = $sheet->getHighestColumn();
        $borders    = $sheet->getStyle('A' . $startTableRow . ':' . $highestCol . $endTableRow)
            ->getBorders();
        $borderStyle = Border::BORDER_THIN;
        $borders->getLeft()->setBorderStyle($borderStyle);
        $borders->getRight()->setBorderStyle($borderStyle);
        $borders->getTop()->setBorderStyle($borderStyle);
        $borders->getBottom()->setBorderStyle($borderStyle);
        $borders->getInside()->setBorderStyle($borderStyle);

        return $spreadsheet;
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

    public function exportExcel()
    {
        $spreadsheet = self::createSpreadsheet($this->formSubmission);
        $excel       = new Xlsx($spreadsheet);
        $fileName    = $this->getFileName('xlsx');

        return Response::streamDownload(fn() => $excel->save('php://output'), $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function exportCsv()
    {
        $spreadsheet = self::createSpreadsheet($this->formSubmission);
        $excel       = new Csv($spreadsheet);
        $fileName    = $this->getFileName('csv');

        return Response::streamDownload(fn() => $excel->save('php://output'), $fileName, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function exportPdf()
    {
        $pdf = Pdf::loadView('pdf.form-submission-summary', [
            'formSubmission' => $this->formSubmission,
        ]);
        $pdf->setPaper([0, 0, 72 * 8.5, 72 * 13], 'landscape');

        // replace all non print characters to underscores
        $fileName = $this->getFileName('pdf');
        return Response::streamDownload(fn() => print($pdf->output()), $fileName);
    }
}
