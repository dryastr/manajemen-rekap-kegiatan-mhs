<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Faculty;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ManageDepartementController extends Controller
{
    public function index()
    {
        $departments = Department::with('faculty')->latest()->get();
        $faculties = Faculty::all();

        return view('admin.manage_departments.index', compact('departments', 'faculties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'faculty_id' => 'required|exists:faculties,id',
        ]);

        DB::beginTransaction();
        try {
            Department::create([
                'name' => $request->name,
                'faculty_id' => $request->faculty_id,
            ]);

            DB::commit();
            return redirect()->route('manage-departments.index')->with('success', 'Data jurusan berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambahkan data jurusan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('departments')->ignore($department->id)],
            'faculty_id' => 'required|exists:faculties,id',
        ]);

        DB::beginTransaction();
        try {
            $department->update([
                'name' => $request->name,
                'faculty_id' => $request->faculty_id,
            ]);

            DB::commit();
            return redirect()->route('manage-departments.index')->with('success', 'Data jurusan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui data jurusan: ' . $e->getMessage());
        }
    }

    public function destroy(Department $department)
    {
        DB::beginTransaction();
        try {
            $department->delete();
            DB::commit();
            return redirect()->route('manage-departments.index')->with('success', 'Data jurusan berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus data jurusan: ' . $e->getMessage());
        }
    }
}
