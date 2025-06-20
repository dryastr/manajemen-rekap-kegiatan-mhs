<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faculty;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ManageFacultyController extends Controller
{
    public function index()
    {
        $faculties = Faculty::latest()->get();
        return view('admin.manage_faculties.index', compact('faculties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:faculties,name',
        ]);

        DB::beginTransaction();
        try {
            Faculty::create([
                'name' => $request->name,
            ]);

            DB::commit();
            return redirect()->route('manage-faculties.index')->with('success', 'Data fakultas berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambahkan data fakultas: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Faculty $faculty)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('faculties')->ignore($faculty->id)],
        ]);

        DB::beginTransaction();
        try {
            $faculty->update([
                'name' => $request->name,
            ]);

            DB::commit();
            return redirect()->route('manage-faculties.index')->with('success', 'Data fakultas berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui data fakultas: ' . $e->getMessage());
        }
    }

    public function destroy(Faculty $faculty)
    {
        DB::beginTransaction();
        try {
            $faculty->delete();
            DB::commit();
            return redirect()->route('manage-faculties.index')->with('success', 'Data fakultas berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus data fakultas: ' . $e->getMessage());
        }
    }
}
