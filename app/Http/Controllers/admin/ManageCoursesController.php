<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Department;
use App\Models\Lecturer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ManageCoursesController extends Controller
{
    public function index()
    {
        $courses = Course::with(['department', 'lecturer.user'])->latest()->get();
        $departments = Department::all();
        $lecturers = Lecturer::with('user')->get();

        return view('admin.manage_courses.index', compact('courses', 'departments', 'lecturers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255|unique:courses',
            'name' => 'required|string|max:255',
            'credits' => 'required|integer|min:1',
            'department_id' => 'required|exists:departments,id',
            'lecturer_id' => 'nullable|exists:lecturers,id',
        ]);

        DB::beginTransaction();
        try {
            Course::create([
                'code' => $request->code,
                'name' => $request->name,
                'credits' => $request->credits,
                'department_id' => $request->department_id,
                'lecturer_id' => $request->lecturer_id,
            ]);

            DB::commit();
            return redirect()->route('manage-courses.index')->with('success', 'Data mata kuliah berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambahkan data mata kuliah: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'credits' => 'required|integer|min:1',
            'department_id' => 'required|exists:departments,id',
            'lecturer_id' => 'nullable|exists:lecturers,id',
        ]);

        DB::beginTransaction();
        try {
            $course->update([
                'code' => $request->code,
                'name' => $request->name,
                'credits' => $request->credits,
                'department_id' => $request->department_id,
                'lecturer_id' => $request->lecturer_id,
            ]);

            DB::commit();
            return redirect()->route('manage-courses.index')->with('success', 'Data mata kuliah berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui data mata kuliah: ' . $e->getMessage());
        }
    }

    public function destroy(Course $course)
    {
        DB::beginTransaction();
        try {
            $course->delete();
            DB::commit();
            return redirect()->route('manage-courses.index')->with('success', 'Data mata kuliah berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus data mata kuliah: ' . $e->getMessage());
        }
    }

    public function show(Course $course)
    {
        return response()->json($course->load(['department', 'lecturer.user']));
    }
}
