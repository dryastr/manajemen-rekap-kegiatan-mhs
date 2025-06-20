@extends('layouts.main')

@section('title', 'Kelola Data Dosen')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Daftar Dosen</h4>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#createLecturerModal">
                            Tambah Dosen
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
                                        <th>Nama Dosen</th>
                                        <th>Email</th>
                                        <th>NIDN</th>
                                        <th>Jurusan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lecturers as $lecturer)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $lecturer->name }}</td>
                                            <td>{{ $lecturer->email }}</td>
                                            <td>{{ $lecturer->nidn ?? '-' }}</td>
                                            <td>{{ $lecturer->department->name ?? 'N/A' }}</td>
                                            <td class="text-nowrap">
                                                <div class="dropdown dropup">
                                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                                        id="dropdownMenuButton-{{ $lecturer->id }}"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu"
                                                        aria-labelledby="dropdownMenuButton-{{ $lecturer->id }}">
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0)"
                                                                onclick="openEditModal(
                                                                    '{{ $lecturer->id }}',
                                                                    '{{ $lecturer->name }}',
                                                                    '{{ $lecturer->email }}',
                                                                    '{{ $lecturer->nidn ?? '' }}',
                                                                    '{{ $lecturer->department_id ?? '' }}'
                                                                )">Ubah</a>
                                                        </li>
                                                        <li>
                                                            <form
                                                                action="{{ route('manage-lecturers.destroy', $lecturer->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data dosen ini? Akun user terkait juga akan dihapus!')">
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

    <div class="modal fade" id="createLecturerModal" tabindex="-1" aria-labelledby="createLecturerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createLecturerModalLabel">Tambah Dosen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createLecturerForm" method="POST" action="{{ route('manage-lecturers.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="createName" class="form-label">Nama Dosen</label>
                            <input type="text" class="form-control" id="createName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="createEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="createEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="createNidn" class="form-label">NIDN (Nomor Induk Dosen Nasional)</label>
                            <input type="text" class="form-control" id="createNidn" name="nidn">
                        </div>
                        <div class="mb-3">
                            <label for="createDepartment" class="form-label">Jurusan</label>
                            <select class="form-select" id="createDepartment" name="department_id">
                                <option value="">Pilih Jurusan (Opsional)</option>
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

    <div class="modal fade" id="editLecturerModal" tabindex="-1" aria-labelledby="editLecturerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editLecturerModalLabel">Edit Dosen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editLecturerForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editLecturerId" name="id">
                        <div class="mb-3">
                            <label for="editName" class="form-label">Nama Dosen</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="editNidn" class="form-label">NIDN (Nomor Induk Dosen Nasional)</label>
                            <input type="text" class="form-control" id="editNidn" name="nidn">
                        </div>
                        <div class="mb-3">
                            <label for="editDepartment" class="form-label">Jurusan</label>
                            <select class="form-select" id="editDepartment" name="department_id">
                                <option value="">Pilih Jurusan (Opsional)</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(id, name, email, nidn, departmentId) {
            document.getElementById('editLecturerId').value = id;
            document.getElementById('editName').value = name;
            document.getElementById('editEmail').value = email;
            document.getElementById('editNidn').value = nidn;
            document.getElementById('editDepartment').value = departmentId;
            document.getElementById('editLecturerForm').action = '{{ route('manage-lecturers.update', '') }}/' + id;
            var myModal = new bootstrap.Modal(document.getElementById('editLecturerModal'));
            myModal.show();
        }
    </script>
@endsection
