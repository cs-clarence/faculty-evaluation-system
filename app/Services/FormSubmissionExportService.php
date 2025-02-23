<?php
namespace App\Services;

use App\Models\FormSubmission as Model;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class FormSubmissionExportService
{

    private static function nextChar(string $char, int $increment = 1)
    {
        return chr(ord($char) + $increment);
    }

    private static function createSpreadsheet(
        Model $formSubmission,
        FormSubmissionExportOptions $options,
    ) {

        $showInterpretation = $options->showInterpretation;
        $showReason         = $options->showReason;
        $showText           = $options->showText;
        $showEvaluator      = $options->showText;

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $row         = 1;

        // Details

        $role = $formSubmission->evaluatee->role->display_name;

        $col = 'A';
        $sheet->setCellValue($col . $row, "Evaluatee {$role}");
        $col = self::nextChar($col);
        if ($showEvaluator) {
            $sheet->setCellValue($col . $row, "Evaluator");
            $col = self::nextChar($col);
        }

        $sheet->setCellValue($col . $row, "Form");
        $col = self::nextChar($col);
        $sheet->setCellValue($col . $row, "Submission Period");
        $col = self::nextChar($col);

        if (isset($formSubmission->subject)) {
            $sheet->setCellValue($col . $row, "Subject");
            $col = self::nextChar($col);
        }

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
        if ($showEvaluator) {
            $sheet->setCellValue($col . $row, $formSubmission->evaluator->name);
            $col = self::nextChar($col);
        }
        $sheet->setCellValue($col . $row, $formSubmission->submissionPeriod->form->name);
        $col = self::nextChar($col);
        $sheet->setCellValue($col . $row, $formSubmission->submissionPeriod->name);
        $col = self::nextChar($col);

        if (isset($formSubmission->subject)) {
            $sheet->setCellValue($col . $row, $formSubmission->subject->name);
            $col = self::nextChar($col);
        }
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

    public function getXlsx(
        Model $formSubmission,
        ?FormSubmissionExportOptions $options = null,
    ) {
        $spr = self::createSpreadsheet($formSubmission, $options ?? new FormSubmissionExportOptions());

        return new Xlsx($spr);
    }

    public function getCsv(
        Model $formSubmission,
        ?FormSubmissionExportOptions $options = null,
    ) {
        $spr = self::createSpreadsheet($formSubmission, $options ?? new FormSubmissionExportOptions());

        return new Csv($spr);
    }

    public function getPdf(
        Model $formSubmission,
        ?FormSubmissionExportOptions $options = null,
    ) {

        $pdf = Pdf::loadView('pdf.form-submission-summary', [
            'formSubmission' => $formSubmission,
            'options'        => $options ?? new FormSubmissionExportOptions(),
        ]);
        $pdf->setPaper([0, 0, 72 * 8.5, 72 * 13], 'landscape');

        return $pdf;
    }
}
