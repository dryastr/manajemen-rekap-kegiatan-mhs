<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StudentProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $student = Student::where('user_id', $user->id)->first();

        return view('user.profile.index', compact('user', 'student'));
    }
}
