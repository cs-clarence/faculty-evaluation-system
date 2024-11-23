@extends('layouts.admin')
@section('content')
    <div class="top flex justify-end mb-4">
        <button class="text-2xl text-gray-600">

        </button>
    </div>

    <!-- Main Dashboard Content -->
    <div class="main-dash grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Users and Completed Tasks -->
        <div class="box bg-white shadow-md p-6 rounded-lg flex flex-col items-center space-y-2">
            <div class="totalUser-outer text-center">
                <span class="text-4xl font-bold text-blue-600"></span>
                <span class="text-lg text-gray-700">Total Users</span>
            </div>
            <div class="totalUser-outer text-center mt-4">
                <span class="text-4xl font-bold text-green-600"></span>
                <span class="text-lg text-gray-700">Task Completed!</span>
            </div>
        </div>

        <!-- Facilities -->
        <div class="box bg-white shadow-md p-6 rounded-lg flex flex-col items-center">
            <div class="dashlogo-outer text-center">
                <a href="facilities-dash">
                    <img src="images/dashLogo.png" alt="DHVSU Facilities Logo" class="w-24 h-24 mx-auto">
                </a>
                <label class="text-lg font-semibold text-gray-700">DHVSU FACILITIES</label>
            </div>
        </div>

        <!-- Active Users -->
        <div class="box bg-white shadow-md p-6 rounded-lg text-center">
            <div class="activeUsersOuter">
                <span class="text-2xl font-semibold text-green-600">Online</span>
            </div>
        </div>
    </div>
@endsection
