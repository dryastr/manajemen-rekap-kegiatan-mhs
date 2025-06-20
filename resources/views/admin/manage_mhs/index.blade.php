@extends('layouts.main')

@section('title', 'Kelola Data Mahasiswa')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Daftar Mahasiswa</h4>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#createStudentModal">
                            Tambah Mahasiswa
                        </button>
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-xl">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIM</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Jurusan</th>
                                        <th>IPK</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($students as $student)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $student->nim }}</td>
                                            <td>{{ $student->user->name ?? 'N/A' }}</td>
                                            <td>{{ $student->user->email ?? 'N/A' }}</td>
                                            <td>{{ $student->department->name ?? 'N/A' }}</td>
                                            <td>{{ number_format($student->gpa, 2) }}</td>
                                            <td class="text-nowrap">
                                                <div class="dropdown dropup">
                                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                                        id="dropdownMenuButton-{{ $student->id }}"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu"
                                                        aria-labelledby="dropdownMenuButton-{{ $student->id }}">
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0)"
                                                                onclick="openEditModal(
                                                                    '{{ $student->id }}',
                                                                    '{{ $student->user->name ?? '' }}',
                                                                    '{{ $student->user->email ?? '' }}',
                                                                    '{{ $student->nim }}',
                                                                    '{{ $student->department_id }}',
                                                                    '{{ $student->gpa }}'
                                                                )">Ubah</a>
                                                        </li>
                                                        <li>
                                                            <form
                                                                action="{{ route('manage-mhs.destroy', $student->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus mahasiswa ini?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item">Hapus</button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createStudentModal" tabindex="-1" aria-labelledby="createStudentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createStudentModalLabel">Tambah Mahasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createStudentForm" method="POST" action="{{ route('manage-mhs.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="createName" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="createName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="createEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="createEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="createPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="createPassword" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="createPasswordConfirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="createPasswordConfirmation"
                                name="password_confirmation" required>
                        </div>
                        <div class="mb-3">
                            <label for="createNim" class="form-label">NIM</label>
                            <input type="text" class="form-control" id="createNim" name="nim" required>
                        </div>
                        <div class="mb-3">
                            <label for="createDepartment" class="form-label">Jurusan</label>
                            <select class="form-select" id="createDepartment" name="department_id" required>
                                <option value="">Pilih Jurusan</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editStudentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editStudentModalLabel">Edit Mahasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editStudentForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editStudentId" name="id">
                        <div class="mb-3">
                            <label for="editName" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="editPassword" class="form-label">Password (Kosongkan jika tidak ingin
                                mengubah)</label>
                            <input type="password" class="form-control" id="editPassword" name="password">
                        </div>
                        <div class="mb-3">
                            <label for="editPasswordConfirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="editPasswordConfirmation"
                                name="password_confirmation">
                        </div>
                        <div class="mb-3">
                            <label for="editNim" class="form-label">NIM</label>
                            <input type="text" class="form-control" id="editNim" name="nim" required>
                        </div>
                        <div class="mb-3">
                            <label for="editDepartment" class="form-label">Jurusan</label>
                            <select class="form-select" id="editDepartment" name="department_id" required>
                                <option value="">Pilih Jurusan</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editGpa" class="form-label">IPK</label>
                            <input type="number" step="0.01" class="form-control" id="editGpa" name="gpa"
                                min="0" max="4">
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(id, name, email, nim, departmentId, gpa) {
            document.getElementById('editStudentId').value = id;
            document.getElementById('editName').value = name;
            document.getElementById('editEmail').value = email;
            document.getElementById('editNim').value = nim;
            document.getElementById('editDepartment').value = departmentId;
            document.getElementById('editGpa').value = gpa;
            document.getElementById('editStudentForm').action = '{{ route('manage-mhs.update', '') }}/' + id;
            var myModal = new bootstrap.Modal(document.getElementById('editStudentModal'));
            myModal.show();
        }
    </script>
@endsection
