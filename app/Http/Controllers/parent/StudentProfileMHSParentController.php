<?php

namespace App\Http\Controllers\parent;

use App\Http\Controllers\Controller;
use App\Models\ParentMhs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentProfileMHSParentController extends Controller
{
    public function index()
    {
    $user = Auth::user();

    $parentMhs = ParentMhs::where('user_id', $user->id)->first();

    if (!$parentMhs || !$parentMhs->student) {
        return redirect()->back()->with('error', 'Data profil anak Anda tidak ditemukan atau tidak lengkap.');
    }

    $student = $parentMhs->student->load('department');

    return view('parent.child_profile.index', compact('user', 'student'));
}
}
