<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
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
            'student_id' => 'nullable|string|unique:students,student_id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'password' => Hash::make($request->password),
        ]);

        // Save student data if role is "Student"
        if ($request->role_id == 3) {
            Student::create([
                'user_id' => $user->id, // Link to the user
                'student_id' => $request->student_id,
                'studentName' => $request->studentName,
                'address' => $request->address,
                'course_subject_id' => $request->course_subject_id, // Save course_subject_id
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect()->intended(
            RouteServiceProvider::getDashboard($user->role_id)
        );
    }
}
