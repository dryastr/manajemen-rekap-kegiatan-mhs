<?php

namespace App\Http\Controllers\parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ParentMhs;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;

class ParentMHSController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $parentMhs = ParentMhs::where('user_id', $user->id)->first();

        if (!$parentMhs || !$parentMhs->student) {
            return redirect()->back()->with('error', 'Data anak Anda tidak ditemukan atau tidak lengkap untuk menampilkan dashboard.');
        }

        $student = $parentMhs->student->load('user', 'department');
        $studentId = $student->id;

        $attendanceRecap = Attendance::where('student_id', $studentId)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $totalMeetings = $attendanceRecap->sum();
        $presentCount = $attendanceRecap->get('present', 0);
        $absentCount = $attendanceRecap->get('absent', 0);
        $sickCount = $attendanceRecap->get('sick', 0);
        $permissionCount = $attendanceRecap->get('permission', 0);

        $percentagePresent = $totalMeetings > 0 ? round(($presentCount / $totalMeetings) * 100, 2) : 0;

        $chartData = [
            'type' => 'doughnut',
            'data' => [
                'labels' => ['Hadir', 'Absen', 'Sakit', 'Izin'],
                'datasets' => [
                    [
                        'data' => [$presentCount, $absentCount, $sickCount, $permissionCount],
                        'backgroundColor' => [
                            'rgba(75, 192, 192, 0.8)',
                            'rgba(255, 99, 132, 0.8)',
                            'rgba(255, 205, 86, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                        ],
                    ],
                ],
            ],
            'options' => [
                'title' => ['display' => true, 'text' => 'Proporsi Absensi'],
                'legend' => ['position' => 'right'],
                'responsive' => true,
                'maintainAspectRatio' => false,
                'tooltips' => [
                    'callbacks' => [
                        'label' => 'function(tooltipItem, data) {
                            const dataset = data.datasets[tooltipItem.datasetIndex];
                            const total = dataset.data.reduce((sum, value) => sum + value, 0);
                            const currentValue = dataset.data[tooltipItem.index];
                            const percentage = parseFloat((currentValue / total * 100).toFixed(2));
                            return `${data.labels[tooltipItem.index]}: ${currentValue} (${percentage}%)`;
                        }'
                    ]
                ]
            ],
        ];

        $encodedChartData = urlencode(json_encode($chartData));
        $chartUrl = "https://quickchart.io/chart?c={$encodedChartData}&width=300&height=300&version=2.9.2";


        return view('parent.dashboard', compact(
            'user',
            'student',
            'attendanceRecap',
            'totalMeetings',
            'percentagePresent',
            'chartUrl'
        ));
    }
}
