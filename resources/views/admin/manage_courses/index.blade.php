@extends('layouts.main')

@section('title', 'Kelola Data Mata Kuliah')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Daftar Mata Kuliah</h4>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#createCourseModal">
                            Tambah Mata Kuliah
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
                                        <th>Kode</th>
                                        <th>Nama Mata Kuliah</th>
                                        <th>SKS</th>
                                        <th>Jurusan</th>
                                        <th>Dosen Pengampu</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($courses as $course)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $course->code }}</td>
                                            <td>{{ $course->name }}</td>
                                            <td>{{ $course->credits }}</td>
                                            <td>{{ $course->department->name ?? 'N/A' }}</td>
                                            <td>{{ $course->lecturer->name ?? 'N/A' }}</td>
                                            <td class="text-nowrap">
                                                <div class="dropdown dropup">
                                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                                        id="dropdownMenuButton-{{ $course->id }}"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu"
                                                        aria-labelledby="dropdownMenuButton-{{ $course->id }}">
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0)"
                                                                onclick="openDetailModal(
                                                                    '{{ $course->id }}',
                                                                    '{{ $course->code }}',
                                                                    '{{ $course->name }}',
                                                                    '{{ $course->credits }}',
                                                                    '{{ $course->department->name ?? 'N/A' }}',
                                                                    '{{ $course->lecturer->user->name ?? 'N/A' }}'
                                                                )">Lihat
                                                                Detail</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0)"
                                                                onclick="openEditModal(
                                                                    '{{ $course->id }}',
                                                                    '{{ $course->code }}',
                                                                    '{{ $course->name }}',
                                                                    '{{ $course->credits }}',
                                                                    '{{ $course->department_id }}',
                                                                    '{{ $course->lecturer_id }}'
                                                                )">Ubah</a>
                                                        </li>
                                                        <li>
                                                            <form
                                                                action="{{ route('manage-courses.destroy', $course->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata kuliah ini?')">
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

    <div class="modal fade" id="createCourseModal" tabindex="-1" aria-labelledby="createCourseModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createCourseModalLabel">Tambah Mata Kuliah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createCourseForm" method="POST" action="{{ route('manage-courses.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="createCode" class="form-label">Kode Mata Kuliah</label>
                            <input type="text" class="form-control" id="createCode" name="code" required>
                        </div>
                        <div class="mb-3">
                            <label for="createName" class="form-label">Nama Mata Kuliah</label>
                            <input type="text" class="form-control" id="createName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="createCredits" class="form-label">SKS</label>
                            <input type="number" class="form-control" id="createCredits" name="credits" min="1"
                                required>
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
                        <div class="mb-3">
                            <label for="createLecturer" class="form-label">Dosen Pengampu</label>
                            <select class="form-select" id="createLecturer" name="lecturer_id">
                                <option value="">Pilih Dosen (Opsional)</option>
                                @foreach ($lecturers as $lecturer)
                                    <option value="{{ $lecturer->id }}">{{ $lecturer->name ?? $lecturer->nidn }} ({{ $lecturer->nidn ?? $lecturer->name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editCourseModal" tabindex="-1" aria-labelledby="editCourseModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCourseModalLabel">Edit Mata Kuliah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editCourseForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editCourseId" name="id">
                        <div class="mb-3">
                            <label for="editCode" class="form-label">Kode Mata Kuliah</label>
                            <input type="text" class="form-control" id="editCode" name="code" required>
                        </div>
                        <div class="mb-3">
                            <label for="editName" class="form-label">Nama Mata Kuliah</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editCredits" class="form-label">SKS</label>
                            <input type="number" class="form-control" id="editCredits" name="credits" min="1"
                                required>
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
                            <label for="editLecturer" class="form-label">Dosen Pengampu</label>
                            <select class="form-select" id="editLecturer" name="lecturer_id">
                                <option value="">Pilih Dosen (Opsional)</option>
                                @foreach ($lecturers as $lecturer)
                                    <option value="{{ $lecturer->id }}">{{ $lecturer->user->name ?? $lecturer->nidn }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailCourseModal" tabindex="-1" aria-labelledby="detailCourseModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailCourseModalLabel">Detail Mata Kuliah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Kode Mata Kuliah:</strong> <span id="detailCode"></span></p>
                    <p><strong>Nama Mata Kuliah:</strong> <span id="detailName"></span></p>
                    <p><strong>SKS:</strong> <span id="detailCredits"></span></p>
                    <p><strong>Jurusan:</strong> <span id="detailDepartment"></span></p>
                    <p><strong>Dosen Pengampu:</strong> <span id="detailLecturer"></span></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(id, code, name, credits, departmentId, lecturerId) {
            document.getElementById('editCourseId').value = id;
            document.getElementById('editCode').value = code;
            document.getElementById('editName').value = name;
            document.getElementById('editCredits').value = credits;
            document.getElementById('editDepartment').value = departmentId;
            document.getElementById('editLecturer').value = lecturerId;
            document.getElementById('editCourseForm').action = '{{ route('manage-courses.update', '') }}/' + id;
            var myModal = new bootstrap.Modal(document.getElementById('editCourseModal'));
            myModal.show();
        }

        function openDetailModal(id, code, name, credits, departmentName, lecturerName) {
            document.getElementById('detailCode').textContent = code;
            document.getElementById('detailName').textContent = name;
            document.getElementById('detailCredits').textContent = credits;
            document.getElementById('detailDepartment').textContent = departmentName;
            document.getElementById('detailLecturer').textContent = lecturerName;
            var myModal = new bootstrap.Modal(document.getElementById('detailCourseModal'));
            myModal.show();
        }
    </script>
@endsection
