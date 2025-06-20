<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $student = null;
        if ($user) {
            $student = $user->student;
            if ($student) {
                $student->load('user', 'department');
            }
        }

        $attendanceRecap = collect();
        $totalMeetings = 0;
        $percentagePresent = 0;
        $labels = [];
        $presentData = [];
        $absentData = [];

        if ($student) {
            $studentId = $student->id;

            $attendanceRecap = Attendance::where('student_id', $studentId)
                ->select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status');

            $totalMeetings = $attendanceRecap->sum();
            $presentCount = $attendanceRecap->get('present', 0);
            $percentagePresent = $totalMeetings > 0 ? round(($presentCount / $totalMeetings) * 100, 2) : 0;

            $monthlyAttendance = Attendance::where('student_id', $studentId)
                ->select(
                    DB::raw('DATE_FORMAT(date, "%Y-%m") as month'),
                    'status',
                    DB::raw('count(*) as total')
                )
                ->groupBy('month', 'status')
                ->orderBy('month')
                ->get()
                ->groupBy('month');

            foreach ($monthlyAttendance as $month => $statuses) {
                $labels[] = date('M Y', strtotime($month));
                $currentMonthPresent = 0;
                $currentMonthAbsent = 0;
                foreach ($statuses as $status) {
                    if ($status->status == 'present') {
                        $currentMonthPresent += $status->total;
                    } elseif ($status->status == 'absent') {
                        $currentMonthAbsent += $status->total;
                    }
                }
                $presentData[] = $currentMonthPresent;
                $absentData[] = $currentMonthAbsent;
            }
        }

        return view('user.dashboard', compact(
            'user',
            'student',
            'attendanceRecap',
            'totalMeetings',
            'percentagePresent',
            'labels',
            'presentData',
            'absentData'
        ));
    }
}
