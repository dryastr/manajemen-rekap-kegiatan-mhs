@extends('layouts.main')

@section('title', 'Kelola Data Jurusan')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Daftar Jurusan</h4>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#createDepartmentModal">
                            Tambah Jurusan
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
                                        <th>Nama Jurusan</th>
                                        <th>Fakultas</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($departments as $department)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $department->name }}</td>
                                            <td>{{ $department->faculty->name ?? 'N/A' }}</td>
                                            <td class="text-nowrap">
                                                <div class="dropdown dropup">
                                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                                        id="dropdownMenuButton-{{ $department->id }}"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu"
                                                        aria-labelledby="dropdownMenuButton-{{ $department->id }}">
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0)"
                                                                onclick="openEditModal(
                                                                    '{{ $department->id }}',
                                                                    '{{ $department->name }}',
                                                                    '{{ $department->faculty_id }}'
                                                                )">Ubah</a>
                                                        </li>
                                                        <li>
                                                            <form
                                                                action="{{ route('manage-departments.destroy', $department->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus jurusan ini? Ini akan mempengaruhi data mahasiswa dan mata kuliah yang terkait!')">
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

    <div class="modal fade" id="createDepartmentModal" tabindex="-1" aria-labelledby="createDepartmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createDepartmentModalLabel">Tambah Jurusan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createDepartmentForm" method="POST" action="{{ route('manage-departments.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="createName" class="form-label">Nama Jurusan</label>
                            <input type="text" class="form-control" id="createName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="createFaculty" class="form-label">Fakultas</label>
                            <select class="form-select" id="createFaculty" name="faculty_id" required>
                                <option value="">Pilih Fakultas</option>
                                @foreach ($faculties as $faculty)
                                    <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editDepartmentModal" tabindex="-1" aria-labelledby="editDepartmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDepartmentModalLabel">Edit Jurusan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editDepartmentForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editDepartmentId" name="id">
                        <div class="mb-3">
                            <label for="editName" class="form-label">Nama Jurusan</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editFaculty" class="form-label">Fakultas</label>
                            <select class="form-select" id="editFaculty" name="faculty_id" required>
                                <option value="">Pilih Fakultas</option>
                                @foreach ($faculties as $faculty)
                                    <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
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
        function openEditModal(id, name, facultyId) {
            document.getElementById('editDepartmentId').value = id;
            document.getElementById('editName').value = name;
            document.getElementById('editFaculty').value = facultyId;
            document.getElementById('editDepartmentForm').action = '{{ route('manage-departments.update', '') }}/' +
                id;
            var myModal = new bootstrap.Modal(document.getElementById('editDepartmentModal'));
            myModal.show();
        }
    </script>
@endsection
