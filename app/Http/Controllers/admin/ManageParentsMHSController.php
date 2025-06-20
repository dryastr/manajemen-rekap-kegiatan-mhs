<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ParentMhs;
use App\Models\User;
use App\Models\Student;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ManageParentsMHSController extends Controller
{
    public function index()
    {
        $parents = ParentMhs::with(['user', 'student.user'])->latest()->get();
        $students = Student::with('user')->get();

        return view('admin.manage_parents_mhs.index', compact('parents', 'students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'student_id' => 'required|exists:students,id|unique:parents_mhs,student_id',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $role = Role::where('name', 'parent')->first();

            if (!$role) {
                Log::error('Role "parent" not found during ParentMhs store. Please ensure this role exists in the roles table.');
                throw new \Exception('Peran "parent" tidak ditemukan. Harap tambahkan di tabel roles.');
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $role->id,
            ]);

            ParentMhs::create([
                'user_id' => $user->id,
                'student_id' => $request->student_id,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
            ]);

            DB::commit();
            return redirect()->route('admin.manage-parents-mhs.index')->with('success', 'Akun orang tua berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error adding parent account: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Gagal menambahkan akun orang tua: ' . $e->getMessage());
        }
    }

    public function update(Request $request, ParentMhs $parents_mhs)
    {
        if (is_null($parents_mhs->user)) {
            Log::warning('ParentMhs (ID: ' . $parents_mhs->id . ') has a null user relation during update. Data inconsistency detected.');
            return redirect()->back()->with('error', 'Data pengguna (user) untuk orang tua ini tidak ditemukan. Harap periksa integritas data.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($parents_mhs->user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'student_id' => ['required', 'exists:students,id', Rule::unique('parents_mhs')->ignore($parents_mhs->id)->where(function ($query) use ($request) {
                return $query->where('student_id', $request->student_id);
            })],
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $parents_mhs->user->name = $request->name;
            $parents_mhs->user->email = $request->email;
            if ($request->filled('password')) {
                $parents_mhs->user->password = Hash::make($request->password);
            }
            $parents_mhs->user->save();

            $parents_mhs->student_id = $request->student_id;
            $parents_mhs->phone_number = $request->phone_number;
            $parents_mhs->address = $request->address;
            $parents_mhs->save();

            DB::commit();
            return redirect()->route('manage-parents-mhs.index')->with('success', 'Data orang tua berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating parent account (ParentMhs ID: ' . $parents_mhs->id . '): ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Gagal memperbarui data orang tua: ' . $e->getMessage());
        }
    }

    public function destroy(ParentMhs $parents_mhs)
    {
        DB::beginTransaction();
        try {
            if ($parents_mhs->user) {
                $parents_mhs->user->delete();
            } else {
                $parents_mhs->delete();
            }
            DB::commit();
            return redirect()->route('manage-parents-mhs.index')->with('success', 'Data orang tua berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus data orang tua: ' . $e->getMessage());
        }
    }

    public function show(ParentMhs $parents_mhs)
    {
        return response()->json($parents_mhs->load(['user', 'student.user']));
    }
}
