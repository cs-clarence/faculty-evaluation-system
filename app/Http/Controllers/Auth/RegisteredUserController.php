<?php
namespace App\Http\Controllers\Auth;

use App\Facades\Services\UserService;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Department;
use App\Models\Role;
use App\Models\RoleCode;
use App\Models\SchoolYear;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use stdClass;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $courses = Course::withoutArchived()
            ->has('courseSubjects')
            ->orderBy('name')
            ->orderBy('code')
            ->orderBy('department_id')
            ->lazy();

        $departments = Department::withoutArchived()
            ->orderBy('name')
            ->orderBy('code')
            ->lazy();

        $schoolYear = SchoolYear::active()
            ->orderByDesc('year_start')
            ->lazy();

        return view('auth.register', [
            'roles'       => Role::where('hidden', false)->get(),
            'courses'     => $courses,
            'departments' => $departments,
            'schoolYears' => $schoolYear,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $roleCode      = $request->role_code;
        $isStudent     = $roleCode === RoleCode::Student->value;
        $hasDepartment = $roleCode === RoleCode::Teacher->value || $roleCode === RoleCode::Dean->value;

        $valid = $request->validate([
            'name'                    => ['required', 'string', 'max:255'],
            'email'                   => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password'                => ['required', 'confirmed', Password::default()],
            'student_number'          => $isStudent ? ['required', 'string', 'unique:students,student_number',
                function (string $attribute, mixed $value, Closure $fail) {
                    if (! preg_match('/\\d{10}/', $value)) {
                        $fail("The :attribute must be a 10 digit number.");
                    }
                },
            ] : [],
            'course_id'               => $isStudent ? ['required', 'integer', 'exists:courses,id'] : [],
            'starting_school_year_id' => $isStudent ? ['required', 'integer', 'exists:school_years,id'] : [],
            'department_id'           => $hasDepartment ? ['required', 'integer', 'exists:departments,id'] : [],
            'role_code'               => ['required', 'string', 'exists:roles,code'],
        ]);

        $extraArgs                       = new stdClass;
        $extraArgs->departmentId         = $valid['department_id'] ?? null;
        $extraArgs->studentNumber        = $valid['student_number'] ?? null;
        $extraArgs->courseId             = $valid['course_id'] ?? null;
        $extraArgs->startingSchoolYearId = $valid['starting_school_year_id'] ?? null;

        $user = UserService::create(
            $valid['email'],
            $valid['name'],
            $valid['password'],
            RoleCode::from($valid['role_code']),
            false,
            true,
            $extraArgs,
        );

        event(new Registered($user));

        Auth::login($user);

        return redirect()->intended(
            RouteServiceProvider::getDashboard($roleCode)
        );
    }
}
