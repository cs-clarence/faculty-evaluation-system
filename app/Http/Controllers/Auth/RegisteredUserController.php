<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use RoleCode;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register', [
            'roles' => Role::where('hidden', false)->get(),
            'studentRole' => Role::where('code', 'student')->first(),
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'student_number' => 'nullable|string|unique:students,student_number',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'password' => Hash::make($request->password),
        ]);

        // Save student data if role is "Student"
        $roleCode = Role::whereId($request->role_id)->first(['code'])->code;
        if ($roleCode === RoleCode::Student->value) {
            Student::create([
                'user_id' => $user->id, // Link to the user
                'student_number' => $request->student_number,
                'address' => $request->address,
            ]);
        }

        if ($roleCode === RoleCode::Teacher->value) {
            Teacher::create([
                'user_id' => $user->id, // Link to the user
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect()->intended(
            RouteServiceProvider::getDashboard($roleCode)
        );
    }
}
