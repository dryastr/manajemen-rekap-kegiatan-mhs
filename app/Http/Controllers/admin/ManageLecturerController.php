<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lecturer;
use App\Models\User;
use App\Models\Department;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class ManageLecturerController extends Controller
{
    public function index()
    {
        $lecturers = Lecturer::with('department')->latest()->get();
        $departments = Department::all();
        $availableUsers = collect();

        return view('admin.manage_lecturers.index', compact('lecturers', 'departments', 'availableUsers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email|unique:lecturers,email',
            'nidn' => 'nullable|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        DB::beginTransaction();
        try {
            Lecturer::create([
                'name' => $request->name,
                'email' => $request->email,
                'nidn' => $request->nidn,
                'department_id' => $request->department_id,
            ]);

            DB::commit();
            return redirect()->route('manage-lecturers.index')->with('success', 'Data dosen berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambahkan data dosen: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Lecturer $lecturer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore(User::where('email', $lecturer->email)->first()->id ?? null), Rule::unique('lecturers')->ignore($lecturer->id)],
            'nidn' => ['nullable', 'string', 'max:255', Rule::unique('lecturers')->ignore($lecturer->id)],
            'department_id' => 'nullable|exists:departments,id',
        ]);

        DB::beginTransaction();
        try {
            $user = User::where('email', $lecturer->email)->first();
            if ($user) {
                $user->name = $request->name;
                $user->email = $request->email;
                $user->save();
            } else {
                $roleLecturer = Role::where('name', 'lecturer')->first();
                if ($roleLecturer) {
                    User::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'role_id' => $roleLecturer->id,
                    ]);
                }
            }

            $lecturer->update([
                'name' => $request->name,
                'email' => $request->email,
                'nidn' => $request->nidn,
                'department_id' => $request->department_id,
            ]);

            DB::commit();
            return redirect()->route('manage-lecturers.index')->with('success', 'Data dosen berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui data dosen: ' . $e->getMessage());
        }
    }

    public function destroy(Lecturer $lecturer)
    {
        DB::beginTransaction();
        try {
            $user = User::where('email', $lecturer->email)->first();
            if ($user) {
                $user->delete();
            }

            $lecturer->delete();

            DB::commit();
            return redirect()->route('manage-lecturers.index')->with('success', 'Data dosen berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus data dosen: ' . $e->getMessage());
        }
    }
}
