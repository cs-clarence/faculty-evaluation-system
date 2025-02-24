<?php
namespace App\Livewire\Pages\User\FacultyEvaluations;

use App\Facades\Services\FileNameService;
use App\Livewire\Traits\WithSearch;
use App\Models\FormSubmission;
use App\Models\RoleCode;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination, WithSearch;

    private function getSubmissionsQuery()
    {
        $user            = Auth::user();
        $departmentId    = $user->dean->department_id;
        $formSubmissions = FormSubmission::with([
            'formSubmissionDepartment',
            'formSubmissionSubject.courseSubject.subject',
        ])
            ->whereHas('formSubmissionDepartment',
                fn($q) => $q->whereDepartmentId($departmentId)
            )
            ->whereHas('submissionPeriod',
                fn($q) => $q
                    ->whereHas('evaluateeRole',
                        fn($q) => $q->whereCode(RoleCode::Teacher->value)
                    )
            );

        if ($this->shouldSearch()) {
            $formSubmissions = $formSubmissions->fullTextSearch(
                [
                    'relations' => [
                        'submissionPeriod' => [
                            'columns'   => ['name'],
                            'relations' => [
                                'evaluateeRole' => [
                                    'columns' => ['display_name', 'code'],
                                ],
                            ],
                        ],
                        'evaluatee'        => [
                            'columns' => ['name', 'email'],
                        ],
                    ],
                ],
                $this->searchText
            );
        }

        return $formSubmissions;
    }

    public function render()
    {
        $formSubmissions = $this->getSubmissionsQuery()->cursorPaginate(15);

        return view('livewire.pages.user.faculty-evaluations.index', [
            'formSubmissions' => $formSubmissions,
        ])
            ->layout('components.layouts.user');
    }

    private static function nextCol(string $char)
    {
        return chr(ord($char) + 1);
    }

    /**
     * @param Collection<FormSubmission> $submissions
     */
    private static function createSpreadsheet(Collection $submissions): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $activeSheet = $spreadsheet->getActiveSheet();
        $row         = 1;
        $col         = 'A';

        $activeSheet->setCellValue($col . $row, 'Period');
        $col = self::nextCol($col);
        if (Gate::allows('viewEvaluatorRole', FormSubmission::class)) {
            $activeSheet->setCellValue($col . $row, 'Evaluator Role');
            $col = self::nextCol($col);
        }
        if (Gate::allows('viewEvaluator', FormSubmission::class)) {
            $activeSheet->setCellValue($col . $row, 'Evaluator');
            $col = self::nextCol($col);
        }
        $activeSheet->setCellValue($col . $row, 'Teacher');
        $col = self::nextCol($col);
        $activeSheet->setCellValue($col . $row, 'Subject');
        $col = self::nextCol($col);
        $activeSheet->setCellValue($col . $row, 'Semester');
        $col = self::nextCol($col);
        $activeSheet->setCellValue($col . $row, 'Rating');
        $col = self::nextCol($col);

        $maxCell = $activeSheet->getHighestColumn();
        $style   = $activeSheet->getStyle('A' . $row . ':' . $maxCell . $row);
        $style->getFont()->setBold(true);

        $row++;

        foreach ($submissions as $s) {
            $col = 'A';
            $activeSheet->setCellValue($col . $row, $s->submissionPeriod->name);
            $col = self::nextCol($col);
            if (Gate::allows('viewEvaluatorRole', FormSubmission::class)) {
                $activeSheet->setCellValue($col . $row, $s->submissionPeriod->evaluatorRole->display_name);
                $col = self::nextCol($col);
            }
            if (Gate::allows('viewEvaluator', FormSubmission::class)) {
                $activeSheet->setCellValue($col . $row, $s->evaluator->name);
                $col = self::nextCol($col);
            }
            $activeSheet->setCellValue($col . $row, $s->evaluatee->name);
            $col = self::nextCol($col);
            $activeSheet->setCellValue($col . $row, $s->subject?->name ?? 'N/A');
            $col = self::nextCol($col);
            $activeSheet->setCellValue($col . $row, $s->submissionPeriod->semester ?? 'N/A');
            $col = self::nextCol($col);
            $activeSheet->setCellValue($col . $row, $s->rating . '%');
            $col = self::nextCol($col);

            $row++;
        }

        $maxRow  = $activeSheet->getHighestRow();
        $style   = $activeSheet->getStyle('A' . 1 . ':' . $maxCell . $maxRow);
        $borders = $style->getBorders();
        $borders->getInside()->setBorderStyle(Border::BORDER_THIN);
        $borders->getTop()->setBorderStyle(Border::BORDER_THIN);
        $borders->getBottom()->setBorderStyle(Border::BORDER_THIN);
        $borders->getRight()->setBorderStyle(Border::BORDER_THIN);
        $borders->getLeft()->setBorderStyle(Border::BORDER_THIN);

        return $spreadsheet;
    }

    private static function getFileName(string $ext)
    {
        $now = Carbon::now();

        return FileNameService::createName([
            $now->format('Y-m-d_h-i-s'),
            'Faculty Evalutations',
            Auth::user()->dean?->department->name,
        ], $ext);
    }

    public function exportExcel()
    {
        $data = $this->getSubmissionsQuery()->get();
        $spr  = self::createSpreadsheet($data);
        $xl   = new Xlsx($spr);
        $name = self::getFileName('xlsx');

        return Response::streamDownload(fn() => $xl->save('php://output'), $name);
    }

    public function exportCsv()
    {
        $data = $this->getSubmissionsQuery()->get();
        $spr  = self::createSpreadsheet($data);
        $csv  = new Csv($spr);
        $name = self::getFileName('csv');

        return Response::streamDownload(fn() => $csv->save('php://output'), $name);
    }
}
