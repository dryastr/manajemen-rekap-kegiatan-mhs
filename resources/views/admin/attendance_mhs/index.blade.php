@extends('layouts.main')

@section('title', 'Kelola Data Absensi Mahasiswa')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Daftar Absensi Mahasiswa</h4>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#createAttendanceModal">
                            Tambah Absensi
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
                                        <th>Nama Mahasiswa</th>
                                        <th>Mata Kuliah</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($attendances as $attendance)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $attendance->student->user->name ?? 'N/A' }}</td>
                                            <td>{{ $attendance->course->name ?? 'N/A' }}</td>
                                            <td>{{ $attendance->date->format('d-m-Y') }}</td>
                                            <td>{{ ucfirst($attendance->status) }}</td>
                                            <td class="text-nowrap">
                                                <div class="dropdown dropup">
                                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                                        id="dropdownMenuButton-{{ $attendance->id }}"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu"
                                                        aria-labelledby="dropdownMenuButton-{{ $attendance->id }}">
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0)"
                                                                onclick="openDetailModal(
                                                                    '{{ $attendance->student->user->name ?? 'N/A' }}',
                                                                    '{{ $attendance->course->name ?? 'N/A' }}',
                                                                    '{{ $attendance->date->format('d-m-Y') }}',
                                                                    '{{ ucfirst($attendance->status) }}'
                                                                )">Lihat
                                                                Detail</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0)"
                                                                onclick="openEditModal(
                                                                    '{{ $attendance->id }}',
                                                                    '{{ $attendance->student_id }}',
                                                                    '{{ $attendance->course_id }}',
                                                                    '{{ $attendance->date->format('Y-m-d') }}',
                                                                    '{{ $attendance->status }}'
                                                                )">Ubah</a>
                                                        </li>
                                                        <li>
                                                            <form
                                                                action="{{ route('attendance-mhs.destroy', $attendance->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus absensi ini?')">
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

    <div class="modal fade" id="createAttendanceModal" tabindex="-1" aria-labelledby="createAttendanceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createAttendanceModalLabel">Tambah Absensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createAttendanceForm" method="POST" action="{{ route('attendance-mhs.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="createStudent" class="form-label">Mahasiswa</label>
                            <select class="form-select" id="createStudent" name="student_id" required>
                                <option value="">Pilih Mahasiswa</option>
                                @foreach ($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->user->name ?? $student->nim }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="createCourse" class="form-label">Mata Kuliah</label>
                            <select class="form-select" id="createCourse" name="course_id" required>
                                <option value="">Pilih Mata Kuliah</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->name }} ({{ $course->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="createDate" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="createDate" name="date" required>
                        </div>
                        <div class="mb-3">
                            <label for="createStatus" class="form-label">Status</label>
                            <select class="form-select" id="createStatus" name="status" required>
                                <option value="">Pilih Status</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editAttendanceModal" tabindex="-1" aria-labelledby="editAttendanceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAttendanceModalLabel">Edit Absensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editAttendanceForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editAttendanceId" name="id">
                        <div class="mb-3">
                            <label for="editStudent" class="form-label">Mahasiswa</label>
                            <select class="form-select" id="editStudent" name="student_id" required>
                                <option value="">Pilih Mahasiswa</option>
                                @foreach ($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->user->name ?? $student->nim }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editCourse" class="form-label">Mata Kuliah</label>
                            <select class="form-select" id="editCourse" name="course_id" required>
                                <option value="">Pilih Mata Kuliah</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->name }} ({{ $course->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editDate" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="editDate" name="date" required>
                        </div>
                        <div class="mb-3">
                            <label for="editStatus" class="form-label">Status</label>
                            <select class="form-select" id="editStatus" name="status" required>
                                <option value="">Pilih Status</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailAttendanceModal" tabindex="-1" aria-labelledby="detailAttendanceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailAttendanceModalLabel">Detail Absensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Mahasiswa:</strong> <span id="detailStudentName"></span></p>
                    <p><strong>Mata Kuliah:</strong> <span id="detailCourseName"></span></p>
                    <p><strong>Tanggal:</strong> <span id="detailDate"></span></p>
                    <p><strong>Status:</strong> <span id="detailStatus"></span></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(id, studentId, courseId, date, status) {
            document.getElementById('editAttendanceId').value = id;
            document.getElementById('editStudent').value = studentId;
            document.getElementById('editCourse').value = courseId;
            document.getElementById('editDate').value = date;
            document.getElementById('editStatus').value = status;
            document.getElementById('editAttendanceForm').action = '{{ route('attendance-mhs.update', '') }}/' + id;
            var myModal = new bootstrap.Modal(document.getElementById('editAttendanceModal'));
            myModal.show();
        }

        function openDetailModal(studentName, courseName, date, status) {
            document.getElementById('detailStudentName').textContent = studentName;
            document.getElementById('detailCourseName').textContent = courseName;
            document.getElementById('detailDate').textContent = date;
            document.getElementById('detailStatus').textContent = status;
            var myModal = new bootstrap.Modal(document.getElementById('detailAttendanceModal'));
            myModal.show();
        }
    </script>
@endsection
