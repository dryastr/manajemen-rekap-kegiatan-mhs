<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\Faculty;
use App\Models\Department;
use App\Models\Lecturer;
use App\Models\Course;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $totalStudents = Student::count();
        $totalFaculties = Faculty::count();
        $totalDepartments = Department::count();
        $totalLecturers = Lecturer::count();
        $totalCourses = Course::count();
        $totalUsers = User::count();

        $attendanceSummary = Attendance::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $totalAttendances = $attendanceSummary->sum();
        $presentPercentage = $totalAttendances > 0 ? round(($attendanceSummary->get('present', 0) / $totalAttendances) * 100, 2) : 0;
        $absentPercentage = $totalAttendances > 0 ? round(($attendanceSummary->get('absent', 0) / $totalAttendances) * 100, 2) : 0;

        $studentsPerDepartment = Department::withCount('students')->get();
        $coursesPerDepartment = Department::withCount('courses')->get();

        $attendanceByMonth = Attendance::select(
            DB::raw('DATE_FORMAT(date, "%Y-%m") as month'),
            DB::raw('COUNT(*) as total_records'),
            DB::raw('SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present_count'),
            DB::raw('SUM(CASE WHEN status = "absent" THEN 1 ELSE 0 END) as absent_count')
        )
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        $chartLabels = [];
        $chartPresentData = [];
        $chartAbsentData = [];
        $chartTotalRecords = [];

        foreach ($attendanceByMonth as $data) {
            $chartLabels[] = date('M Y', strtotime($data->month));
            $chartPresentData[] = $data->present_count;
            $chartAbsentData[] = $data->absent_count;
            $chartTotalRecords[] = $data->total_records;
        }

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalFaculties',
            'totalDepartments',
            'totalLecturers',
            'totalCourses',
            'totalUsers',
            'attendanceSummary',
            'totalAttendances',
            'presentPercentage',
            'absentPercentage',
            'studentsPerDepartment',
            'coursesPerDepartment',
            'chartLabels',
            'chartPresentData',
            'chartAbsentData',
            'chartTotalRecords'
        ));
    }
}
