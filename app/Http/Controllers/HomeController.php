<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {

        // Check if the user is authenticated
        if(Auth::id()) {
            $role_id = Auth()->user()->role_id;
            // Redirect based on role_id
            if ($role_id == '2') {

                return view('/admin/admindashboard');

            } elseif ($role_id == '3') {

                return view('/dashboard');

            }
        }
    }
}
