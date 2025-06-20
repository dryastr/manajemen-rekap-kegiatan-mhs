<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Faculty;
use App\Models\Department;
use App\Models\Lecturer;
use App\Models\Course;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;

class LandingController extends Controller
{
    public function index()
    {
        $totalStudents = Student::count();
        $totalFaculties = Faculty::count();
        $totalDepartments = Department::count();
        $totalLecturers = Lecturer::count();
        $totalCourses = Course::count();

        $attendanceSummary = Attendance::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $totalAttendanceRecords = $attendanceSummary->sum();
        $presentCount = $attendanceSummary->get('present', 0);
        $absentCount = $attendanceSummary->get('absent', 0);
        $sickCount = $attendanceSummary->get('sick', 0);
        $permissionCount = $attendanceSummary->get('permission', 0);

        $attendanceChartData = [
            'labels' => ['Hadir', 'Absen', 'Sakit', 'Izin'],
            'data' => [$presentCount, $absentCount, $sickCount, $permissionCount],
            'colors' => [
                'rgba(75, 192, 192, 0.8)',
                'rgba(255, 99, 132, 0.8)',
                'rgba(255, 205, 86, 0.8)',
                'rgba(54, 162, 235, 0.8)',
            ],
        ];

        $studentsPerDepartment = Department::withCount('students')->get();
        $studentsPerDepartmentLabels = $studentsPerDepartment->pluck('name');
        $studentsPerDepartmentData = $studentsPerDepartment->pluck('students_count');

        $coursesPerDepartment = Department::withCount('courses')->get();
        $coursesPerDepartmentLabels = $coursesPerDepartment->pluck('name');
        $coursesPerDepartmentData = $coursesPerDepartment->pluck('courses_count');

        $sampleDepartment = Department::with(['courses' => function ($query) {
            $query->limit(5);
        }])->first();

        $sampleCourses = $sampleDepartment ? $sampleDepartment->courses : collect();

        return view('landing', compact(
            'totalStudents',
            'totalFaculties',
            'totalDepartments',
            'totalLecturers',
            'totalCourses',
            'attendanceChartData',
            'studentsPerDepartmentLabels',
            'studentsPerDepartmentData',
            'coursesPerDepartmentLabels',
            'coursesPerDepartmentData',
            'sampleDepartment',
            'sampleCourses',
            'totalAttendanceRecords'
        ));
    }
}
