<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AttendanceMHSController extends Controller
{
    public function index()
    {
        $attendances = Attendance::with(['student.user', 'course'])->latest()->get();
        $students = Student::with('user')->get();
        $courses = Course::all();
        $statuses = ['present', 'absent', 'sick', 'permission'];

        return view('admin.attendance_mhs.index', compact('attendances', 'students', 'courses', 'statuses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'date' => [
                'required',
                'date',
                Rule::unique('attendances')->where(function ($query) use ($request) {
                    return $query->where('student_id', $request->student_id)
                        ->where('course_id', $request->course_id)
                        ->where('date', $request->date);
                })
            ],
            'status' => 'required|in:present,absent,sick,permission',
        ]);

        DB::beginTransaction();
        try {
            Attendance::create([
                'student_id' => $request->student_id,
                'course_id' => $request->course_id,
                'date' => $request->date,
                'status' => $request->status,
            ]);

            DB::commit();
            return redirect()->route('attendance-mhs.index')->with('success', 'Data absensi berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambahkan data absensi: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Attendance $manage_mhs)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'date' => [
                'required',
                'date',
                Rule::unique('attendances')->ignore($manage_mhs->id)->where(function ($query) use ($request) {
                    return $query->where('student_id', $request->student_id)
                        ->where('course_id', $request->course_id)
                        ->where('date', $request->date);
                })
            ],
            'status' => 'required|in:present,absent,sick,permission',
        ]);

        DB::beginTransaction();
        try {
            $manage_mhs->update([
                'student_id' => $request->student_id,
                'course_id' => $request->course_id,
                'date' => $request->date,
                'status' => $request->status,
            ]);

            DB::commit();
            return redirect()->route('attendance-mhs.index')->with('success', 'Data absensi berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui data absensi: ' . $e->getMessage());
        }
    }

    public function destroy(Attendance $manage_mhs)
    {
        DB::beginTransaction();
        try {
            $manage_mhs->delete();
            DB::commit();
            return redirect()->route('attendance-mhs.index')->with('success', 'Data absensi berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus data absensi: ' . $e->getMessage());
        }
    }

    public function show(Attendance $manage_mhs)
    {
        return response()->json($manage_mhs->load(['student.user', 'course']));
    }
}
