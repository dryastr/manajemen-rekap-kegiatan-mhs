<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\Department;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class ManageMHSController extends Controller
{
    public function index()
    {
        $students = Student::with(['user', 'department'])->latest()->get();
        $departments = Department::all();
        return view('admin.manage_mhs.index', compact('students', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'nim' => 'required|string|max:255|unique:students',
            'department_id' => 'required|exists:departments,id',
        ]);

        DB::beginTransaction();
        try {
            $role = Role::where('name', 'user')->first();

            if (!$role) {
                throw new \Exception('Peran "user" tidak ditemukan. Harap tambahkan di tabel roles.');
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $role->id,
            ]);

            Student::create([
                'user_id' => $user->id,
                'nim' => $request->nim,
                'department_id' => $request->department_id,
            ]);

            DB::commit();
            return redirect()->route('manage_mhs.index')->with('success', 'Data mahasiswa berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambahkan data mahasiswa: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Student $manage_mh)
    {
        // dd($manage_mh);
        if (is_null($manage_mh->user)) {
            return redirect()->back()->with('error', 'Data pengguna (user) untuk mahasiswa ini tidak ditemukan. Harap periksa integritas data.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($manage_mh->user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'nim' => ['required', 'string', 'max:255', Rule::unique('students')->ignore($manage_mh->id)],
            'department_id' => 'required|exists:departments,id',
            'gpa' => 'nullable|numeric|between:0,4',
        ]);

        DB::beginTransaction();
        try {
            $manage_mh->user->name = $request->name;
            $manage_mh->user->email = $request->email;
            if ($request->filled('password')) {
                $manage_mh->user->password = Hash::make($request->password);
            }
            $manage_mh->user->save();

            $manage_mh->nim = $request->nim;
            $manage_mh->department_id = $request->department_id;
            $manage_mh->gpa = $request->gpa;
            $manage_mh->save();

            DB::commit();
            return redirect()->route('manage-mhs.index')->with('success', 'Data mahasiswa berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui data mahasiswa: ' . $e->getMessage());
        }
    }

    public function destroy(Student $manage_mh)
    {
        DB::beginTransaction();
        try {
            if ($manage_mh->user) {
                $manage_mh->user->delete();
            } else {
                $manage_mh->delete(); 
            }
            DB::commit();
            return redirect()->route('admin.manage-mhs.index')->with('success', 'Data mahasiswa berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus data mahasiswa: ' . $e->getMessage());
        }
    }
}
