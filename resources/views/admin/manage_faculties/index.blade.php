@extends('layouts.main')

@section('title', 'Kelola Data Fakultas')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Daftar Fakultas</h4>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#createFacultyModal">
                            Tambah Fakultas
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
                                        <th>Nama Fakultas</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($faculties as $faculty)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $faculty->name }}</td>
                                            <td class="text-nowrap">
                                                <div class="dropdown dropup">
                                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                                        id="dropdownMenuButton-{{ $faculty->id }}"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu"
                                                        aria-labelledby="dropdownMenuButton-{{ $faculty->id }}">
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0)"
                                                                onclick="openEditModal(
                                                                    '{{ $faculty->id }}',
                                                                    '{{ $faculty->name }}'
                                                                )">Ubah</a>
                                                        </li>
                                                        <li>
                                                            <form
                                                                action="{{ route('manage-faculties.destroy', $faculty->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus fakultas ini? Ini akan mempengaruhi data jurusan yang terkait!')">
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

    <div class="modal fade" id="createFacultyModal" tabindex="-1" aria-labelledby="createFacultyModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createFacultyModalLabel">Tambah Fakultas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createFacultyForm" method="POST" action="{{ route('manage-faculties.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="createName" class="form-label">Nama Fakultas</label>
                            <input type="text" class="form-control" id="createName" name="name" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editFacultyModal" tabindex="-1" aria-labelledby="editFacultyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFacultyModalLabel">Edit Fakultas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editFacultyForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editFacultyId" name="id">
                        <div class="mb-3">
                            <label for="editName" class="form-label">Nama Fakultas</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(id, name) {
            document.getElementById('editFacultyId').value = id;
            document.getElementById('editName').value = name;
            document.getElementById('editFacultyForm').action = '{{ route('manage-faculties.update', '') }}/' + id;
            var myModal = new bootstrap.Modal(document.getElementById('editFacultyModal'));
            myModal.show();
        }
    </script>
@endsection
