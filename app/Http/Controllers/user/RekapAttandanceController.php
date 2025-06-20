<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Course;
use Illuminate\Support\Facades\DB;

class RekapAttandanceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->student) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini atau data mahasiswa Anda tidak lengkap.');
        }

        $studentId = $user->student->id;
        $selectedCourseId = $request->input('course_id');

        $coursesAttended = Attendance::where('student_id', $studentId)
            ->select('course_id')
            ->distinct()
            ->with('course')
            ->get()
            ->pluck('course', 'course_id');

        $attendances = collect();

        if ($selectedCourseId && $coursesAttended->has($selectedCourseId)) {
            $attendances = Attendance::where('student_id', $studentId)
                ->where('course_id', $selectedCourseId)
                ->orderBy('date', 'asc')
                ->get();
        } else {
            if ($coursesAttended->isNotEmpty()) {
                $firstCourseId = $coursesAttended->keys()->first();
                $attendances = Attendance::where('student_id', $studentId)
                    ->where('course_id', $firstCourseId)
                    ->orderBy('date', 'asc')
                    ->get();
                $selectedCourseId = $firstCourseId;
            }
        }

        $recap = Attendance::where('student_id', $studentId)
            ->select('course_id', 'status', DB::raw('count(*) as total'))
            ->groupBy('course_id', 'status')
            ->get()
            ->groupBy('course_id');

        $formattedRecap = collect();
        foreach ($recap as $courseId => $statuses) {
            $course = $coursesAttended->get($courseId);
            if ($course) {
                $totalMeetings = 0;
                $statusCounts = [
                    'present' => 0,
                    'absent' => 0,
                    'sick' => 0,
                    'permission' => 0,
                ];
                foreach ($statuses as $status) {
                    $statusCounts[$status->status] = $status->total;
                    $totalMeetings += $status->total;
                }

                $percentagePresent = $totalMeetings > 0 ? ($statusCounts['present'] / $totalMeetings) * 100 : 0;

                $formattedRecap->put($courseId, [
                    'course_name' => $course->name,
                    'course_code' => $course->code,
                    'total_meetings' => $totalMeetings,
                    'present' => $statusCounts['present'],
                    'absent' => $statusCounts['absent'],
                    'sick' => $statusCounts['sick'],
                    'permission' => $statusCounts['permission'],
                    'percentage_present' => round($percentagePresent, 2),
                ]);
            }
        }

        return view('user.rekap_attendance.index', compact('attendances', 'coursesAttended', 'selectedCourseId', 'formattedRecap'));
    }
}
