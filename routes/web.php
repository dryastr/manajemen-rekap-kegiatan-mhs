<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\AttendanceMHSController;
use App\Http\Controllers\admin\ManageCoursesController;
use App\Http\Controllers\admin\ManageDepartementController;
use App\Http\Controllers\admin\ManageFacultyController;
use App\Http\Controllers\admin\ManageLecturerController;
use App\Http\Controllers\admin\ManageMHSController;
use App\Http\Controllers\admin\ManageParentsMHSController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\parent\ParentController;
use App\Http\Controllers\parent\ParentMHSController;
use App\Http\Controllers\parent\RekapAttandanceMHSParentController;
use App\Http\Controllers\parent\StudentProfileMHSParentController;
use App\Http\Controllers\user\RekapAttandanceController;
use App\Http\Controllers\user\StudentProfileController;
use App\Http\Controllers\user\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', [LandingController::class, 'index'])->name('landing.index');

Auth::routes(['middleware' => ['redirectIfAuthenticated']]);


Route::middleware(['auth', 'role.admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::resource('manage-mhs', ManageMHSController::class)
        ->except(['create', 'show', 'edit'])
        ->parameters(['manage-mhs' => 'manage_mh']);
    Route::resource('manage-courses', ManageCoursesController::class)->except(['create', 'show', 'edit'])->parameters(['manage-courses' => 'course']);
    // Route::resource('attendance-mhs', AttendanceMHSController::class)->except(['create', 'show', 'edit']);
    Route::resource('attendance-mhs', AttendanceMHSController::class)
        ->except(['create', 'show', 'edit'])
        ->parameters(['attendance-mhs' => 'attendance_mhs']);
    Route::resource('manage-parents-mhs', ManageParentsMHSController::class)->except(['create', 'show', 'edit'])
        ->parameters(['manage-parents-mhs' => 'parents_mhs']);
    Route::resource('manage-departments', ManageDepartementController::class)->except(['create', 'show', 'edit'])->parameters(['manage-departments' => 'department']);
    Route::resource('manage-faculties', ManageFacultyController::class)->except(['create', 'show', 'edit'])->parameters(['manage-faculties' => 'faculty']);
    Route::resource('manage-lecturers', ManageLecturerController::class)->except(['create', 'show', 'edit'])->parameters(['manage-lecturers' => 'lecturer']);
});

Route::middleware(['auth', 'role.user'])->group(function () {
    Route::get('/home', [UserController::class, 'index'])->name('home');

    Route::get('/profile-mhs', [StudentProfileController::class, 'index'])->name('profile-mhs.index');
    Route::get('/rekap-absensi', [RekapAttandanceController::class, 'index'])->name('rekap-absensi.index');
});

Route::middleware(['auth', 'role.parent'])->group(function () {
    Route::get('/parent', [ParentMHSController::class, 'index'])->name('parent.dashboard');

    Route::get('/profile-mhs-parent', [StudentProfileMHSParentController::class, 'index'])->name('profile-mhs-parent.index');
    Route::get('/rekap-absensi-mhs', [RekapAttandanceMHSParentController::class, 'index'])->name('rekap-absensi-mhs.index');
});
