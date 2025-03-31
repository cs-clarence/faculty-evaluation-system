<?php
namespace App\Livewire\Pages\Admin\Students;

use App\Facades\Services\UserService;
use App\Jobs\SendGeneratedPasswordEmail;
use App\Livewire\Forms\UserForm;
use App\Livewire\Traits\WithSearch;
use App\Models\Course;
use App\Models\Department;
use App\Models\Import\StudentImport;
use App\Models\RoleCode;
use App\Models\SchoolYear;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use stdClass;

class Index extends Component
{
    use WithSearch, WithPagination, WithoutUrlPagination, WithFileUploads;
    public bool $isFormOpen = false;
    public ?User $model;
    public UserForm $form;

    public ?int $filter_department_id = null;
    public ?int $filter_course_id     = null;

    #[Validate(['file', 'required', 'max:10240'])]
    public UploadedFile|File|null $import_file = null;

    public function render()
    {
        $courses = Course::withoutArchived()
            ->has('courseSubjects')
            ->orderBy('department_id')
            ->orderBy('code')
            ->orderBy('name')
            ->get();

        $schoolYears = SchoolYear::active()
            ->orderByDesc('year_start')
            ->orderByDesc('year_end')
            ->lazy();

        $departmentFilters  = Department::has('courses')->get();
        $courseFilters      = $courses;
        $selectedDepartment = isset($this->filter_department_id)
        ? $departmentFilters->where('id', $this->filter_department_id)->first()
        : null;

        if (isset($this->filter_department_id)) {
        }

        if (isset($selectedDepartment)) {
            $courseFilters = $selectedDepartment->courses()
                ->has('courseSemesters')
                ->get();
        }

        $selectedCourse = $courseFilters->where('id', $this->filter_course_id)->first();

        $students = User::roleStudent()
            ->with([
                'student' => fn(HasOne $student) =>
                $student->with(['studentSubjects', 'studentSemesters', 'course'])
                    ->withCount(['studentSubjects', 'studentSemesters']),
            ]);

        if (isset($selectedDepartment) || isset($selectedCourse)) {
            $courseIds = [];
            if (! isset($selectedCourse)) {
                $courseIds = $courseFilters->pluck('id');
            } else {
                $courseIds = [$selectedCourse->id];

            }

            $students = $students->whereHas(
                'student',
                fn($q) => $q->whereIn('course_id', $courseIds)
            );
        } else {
            $students = $students->has('student');
        }

        if ($this->shouldSearch()) {
            $students = $students->fullTextSearch([
                'columns'   => ['name', 'email'],
                'relations' => [
                    'student' => [
                        'columns'   => ['student_number'],
                        'relations' => [
                            'course' => [
                                'columns' => ['name', 'code'],
                            ],
                        ],
                    ],
                ],
            ], $this->searchText);
        }

        $students = $students->cursorPaginate(15);

        return view('livewire.pages.admin.students.index',
            [
                'departmentFilters' => $departmentFilters,
                'courseFilters'     => $courseFilters,
                'courses'           => $courses,
                'schoolYears'       => $schoolYears,
                'students'          => $students,
            ]
        )
            ->layout('components.layouts.admin');
    }

    public function openForm()
    {
        $this->form->role_code = RoleCode::Student->value;
        $this->isFormOpen      = true;
        $this->form->prefill();
    }

    public function resetFilters()
    {
        $this->filter_department_id = null;
        $this->filter_course_id     = null;
        $this->searchText           = null;
    }

    public function closeForm()
    {
        $this->isFormOpen = false;
        $this->model      = null;
        $this->form->clear();
    }

    public function save()
    {
        $this->form->submit();
        $this->closeForm();
    }

    public function edit(User $model)
    {
        $model->load(['student', 'teacher']);
        $this->model = $model;
        $this->form->set($model);
        $this->form->setupEdit(includeBase: true);
        $this->openForm();
    }

    public function editPassword(User $model)
    {
        $this->model = $model;
        $this->form->set($model);
        $this->form->setupEdit(includePassword: true);
        $this->openForm();
    }

    public function delete(User $model)
    {
        $model->delete();
    }

    public function archive(User $model)
    {
        $model->archive();
    }

    public function unarchive(User $model)
    {
        $model->unarchive();
    }

    private static function mapImport(array $row, array $indices)
    {
        $studentNumIndex         = $indices['STUDENT NUMBER'];
        $nameIndex               = $indices['NAME'];
        $courseCodeIndex         = $indices['COURSE CODE'];
        $emailIndex              = $indices['EMAIL'];
        $startingSchoolYearIndex = $indices['STARTING SCHOOL YEAR'];

        $sy = Str::of($row[$startingSchoolYearIndex])->split('/\s*\-\s*/')->map(fn($r) => intval($r));

        return new StudentImport(
            studentNumber: $row[$studentNumIndex],
            name: $row[$nameIndex],
            email: $row[$emailIndex],
            courseCode: $row[$courseCodeIndex],
            startingSchoolYearFrom: $sy[0],
            startingSchoolYearTo: $sy[1],
        );

    }

    private static function validateSchoolYear($attr, $value, $fail)
    {
        $q = SchoolYear::whereYearStart($value[0])
            ->whereYearEnd($value[1]);

        if (! $q->exists()) {
            $fail('The :attribute does not exist in the system.');
        }
    }

    public function import()
    {
        $v = validator(
            ['file' => $this->import_file],
            ['file' => ['file', 'required', 'max:10240']]
        );

        if ($v->fails()) {
            session()->flash('alert-danger', [
                'text' => $v->errors()->get('file')[0],
            ]);
            return $this->redirectRoute('admin.students.index');
        }
        try {

            $ext = $this->import_file->getClientOriginalExtension();

            $fName = $this->import_file->storeAs('imports', 'import_file' . '.' . $ext);
            $fpath = base_path('storage/app/' . $fName);
            if (! $fName) {
                session()->flash('alert-danger', [
                    'text' => "Failed to upload file",
                ]);
                return $this->redirectRoute('admin.students.index');
            }

            $fType = IOFactory::identify($fpath);

            $reader = IOFactory::createReader($fType);
            $reader->setLoadSheetsOnly(['IMPORTS']);
            $spreadsheet = $reader->load($fpath);
            if (! $spreadsheet->sheetNameExists('IMPORTS')) {
                session()->flash('alert-danger', [
                    'text' => "No 'IMPORTS' sheet found",
                ]);
                return $this->redirectRoute('admin.students.index');
            }

            $worksheet = $spreadsheet->getSheetByName('IMPORTS');
            $maxCol    = $worksheet->getHighestColumn();
            $maxRow    = $worksheet->getHighestRow();
            $headers   = collect($worksheet->rangeToArray('A1' . ':' . $maxCol . '1'))
                ->flatten()
                ->map(fn($col) => Str::upper($col));
            $studentNumIndex = $headers->search('STUDENT NUMBER');
            $nameIndex       = $headers->search('NAME');
            $emailIndex      = $headers->search('EMAIL');
            $courseCodeIndex = $headers->search('COURSE CODE');
            $startingSyIndex = $headers->search('STARTING SCHOOL YEAR');
            $indices         = [
                'STUDENT NUMBER'       => $studentNumIndex,
                'NAME'                 => $nameIndex,
                'EMAIL'                => $emailIndex,
                'COURSE CODE'          => $courseCodeIndex,
                'STARTING SCHOOL YEAR' => $startingSyIndex,
            ];

            $data = collect($worksheet->rangeToArray('A2' . ':' . $maxCol . $maxRow));
            $data = $data->map(fn($row) => self::mapImport($row, $indices));

            // validate data per row
            $rowErrors = [];
            $emails    = $data
                ->pluck('email')
                ->map(fn($d) => Str::lower($d));
            $studentNumbers = $data
                ->pluck('studentNumber')
                ->map(fn($d) => Str::lower($d));
            $duplicateEmailInTemplate = function ($attr, $value, $fail) use ($emails) {
                if ($emails->filter(fn($d) => $d === Str::lower($value))->count() > 1) {
                    $fail('The :attribute has a duplicate in the template.');
                }
            };
            $duplicateStudentNumberInTemplate = function ($attr, $value, $fail) use ($studentNumbers) {
                if ($studentNumbers->filter(fn($d) => $d === Str::lower($value))->count() > 1) {
                    $fail('The :attribute has a duplicate in the template.');
                }
            };
            $courseHasSemesters = function ($attr, $value, $fail) {
                if (! Course::has('courseSemesters')->whereCode($value)->exists()) {
                    $fail('The course has no available semesters in the system.');
                }
            };
            foreach ($data as $key => $row) {
                $v = validator([
                    'student_number'       => $row->studentNumber,
                    'email'                => $row->email,
                    'course_code'          => $row->courseCode,
                    'name'                 => $row->name,
                    'starting_school_year' => [$row->startingSchoolYearFrom, $row->startingSchoolYearTo],
                ], [
                    'student_number'       => ['required', 'unique:students,student_number', $duplicateStudentNumberInTemplate],
                    'email'                => ['required', 'unique:users,email', $duplicateEmailInTemplate],
                    'course_code'          => ['required', 'exists:courses,code', $courseHasSemesters],
                    'name'                 => ['required', 'string'],
                    'starting_school_year' => [
                        'required',
                        fn($attr, $value, $fail) => self::validateSchoolYear($attr, $value, $fail),
                    ],
                ]);

                $errors = $v->errors();
                if ($errors->isNotEmpty()) {
                    $rowErrors[$key] = collect($errors->toArray())->flatten()->join(' ');
                }
            }

            if (count($rowErrors) > 0) {
                $remarksCol = self::nextCol($maxCol);
                $worksheet->setCellValue($remarksCol . '1', 'REMARKS');
                $worksheet->getStyle($remarksCol . '1')->getFont()->setBold(true);
                foreach ($rowErrors as $key => $errors) {
                    $worksheet->setCellValue($remarksCol . ($key + 2), $errors);
                }

                $xlsx = new Xlsx($spreadsheet);
                session()->now('alert-success', [
                    'text' => "Some rows have errors. Please check the downloaded file.",
                ]);
                return Response::streamDownload(
                    fn() => $xlsx->save('php://output'),
                    'import-file-with-errors.xlsx'
                );
            }

            $courseCodes       = $data->map(fn($d) => $d->courseCode)->unique();
            $startingYearStart = $data->map(
                fn($d) => $d->startingSchoolYearFrom
            )->unique();
            $startingYearEnd = $data->map(
                fn($d) => $d->startingSchoolYearTo
            )->unique();

            $courses     = Course::whereCode($courseCodes)->get();
            $schoolYears = SchoolYear::whereYearStart($startingYearStart)
                ->whereYearEnd($startingYearEnd)
                ->get();

            $emailSends = DB::transaction(function () use ($data, $courses, $schoolYears) {
                $emailSends = [];
                foreach ($data as $row) {
                    $randomPass              = Str::random();
                    $emailSends[$row->email] = $randomPass;
                    $extras                  = new stdClass;
                    $extras->studentNumber   = $row->studentNumber;
                    $extras->courseId        = $courses
                        ->filter(fn($c) => $c->code === $row->courseCode)->first()?->id;
                    $extras->startingSchoolYearId = $schoolYears
                        ->filter(fn($c) => $c->year_start === $row->startingSchoolYearFrom && $c->year_end === $row->startingSchoolYearTo)->first()?->id;
                    UserService::create(
                        $row->email,
                        $row->name,
                        $randomPass,
                        RoleCode::Student,
                        true,
                        true,
                        $extras,
                    );
                }
                return $emailSends;
            });

            // This should be a background job since it can take long
            foreach ($emailSends as $email => $password) {
                SendGeneratedPasswordEmail::dispatch($email, $password);
            }

            $count = $data->count();
            session()->flash('alert-success', [
                'text' => "Succesfully imported {$count} student(s)",
            ]);
            return $this->redirectRoute('admin.students.index');
        } catch (Exception $e) {
            session()->flash('alert-danger', [
                'text' => 'Error Occured: ' . $e->getMessage(),
            ]);
            return $this->redirectRoute('admin.students.index');
        }
    }

    private static function nextCol(string $col)
    {
        return chr(ord($col) + 1);
    }

    public function downloadImportTemplate()
    {
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('IMPORTS');
        $col = 'A';
        $row = 1;

        $sheet->setCellValue($col . $row, 'STUDENT NUMBER');
        $col = self::nextCol($col);
        $sheet->setCellValue($col . $row, 'NAME');
        $col = self::nextCol($col);
        $sheet->setCellValue($col . $row, 'EMAIL');
        $col = self::nextCol($col);
        $sheet->setCellValue($col . $row, 'COURSE CODE');
        $col = self::nextCol($col);
        $sheet->setCellValue($col . $row, 'STARTING SCHOOL YEAR');

        $sheet->getStyle('A' . $row . ':' . $col . $row)->getFont()->setBold(true);

        $xlsxWriter = new Xlsx($spreadsheet);

        $fileName = 'import-template.xlsx';

        return Response::streamDownload(fn() => $xlsxWriter->save('php://output'), $fileName);
    }
}
