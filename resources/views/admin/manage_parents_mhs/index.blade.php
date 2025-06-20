@extends('layouts.main')

@section('title', 'Kelola Akun Orang Tua')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Daftar Akun Orang Tua</h4>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#createParentModal">
                            Tambah Akun Orang Tua
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
                                        <th>Nama Orang Tua</th>
                                        <th>Email</th>
                                        <th>No. Telepon</th>
                                        <th>Mahasiswa Terkait</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($parents as $parent)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $parent->user->name ?? 'N/A' }}</td>
                                            <td>{{ $parent->user->email ?? 'N/A' }}</td>
                                            <td>{{ $parent->phone_number ?? '-' }}</td>
                                            <td>{{ $parent->student->user->name ?? 'N/A' }}
                                                ({{ $parent->student->nim ?? 'N/A' }})</td>
                                            <td class="text-nowrap">
                                                <div class="dropdown dropup">
                                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                                        id="dropdownMenuButton-{{ $parent->id }}"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu"
                                                        aria-labelledby="dropdownMenuButton-{{ $parent->id }}">
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0)"
                                                                onclick="openDetailModal(
                                                                    '{{ $parent->user->name ?? 'N/A' }}',
                                                                    '{{ $parent->user->email ?? 'N/A' }}',
                                                                    '{{ $parent->phone_number ?? '-' }}',
                                                                    '{{ $parent->address ?? '-' }}',
                                                                    '{{ $parent->student->user->name ?? 'N/A' }} ({{ $parent->student->nim ?? 'N/A' }})'
                                                                )">Lihat
                                                                Detail</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0)"
                                                                onclick="openEditModal(
                                                                    '{{ $parent->id }}',
                                                                    '{{ $parent->user->name ?? '' }}',
                                                                    '{{ $parent->user->email ?? '' }}',
                                                                    '{{ $parent->student_id }}',
                                                                    '{{ $parent->phone_number ?? '' }}',
                                                                    '{{ $parent->address ?? '' }}'
                                                                )">Ubah</a>
                                                        </li>
                                                        <li>
                                                            <form
                                                                action="{{ route('manage-parents-mhs.destroy', $parent->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun orang tua ini?')">
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

    <div class="modal fade" id="createParentModal" tabindex="-1" aria-labelledby="createParentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createParentModalLabel">Tambah Akun Orang Tua</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createParentForm" method="POST" action="{{ route('manage-parents-mhs.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="createName" class="form-label">Nama Orang Tua</label>
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
                            <label for="createStudent" class="form-label">Mahasiswa (Anak)</label>
                            <select class="form-select" id="createStudent" name="student_id" required>
                                <option value="">Pilih Mahasiswa</option>
                                @foreach ($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->user->name ?? $student->nim }}
                                        ({{ $student->nim }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="createPhoneNumber" class="form-label">No. Telepon</label>
                            <input type="text" class="form-control" id="createPhoneNumber" name="phone_number">
                        </div>
                        <div class="mb-3">
                            <label for="createAddress" class="form-label">Alamat</label>
                            <textarea class="form-control" id="createAddress" name="address" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editParentModal" tabindex="-1" aria-labelledby="editParentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editParentModalLabel">Edit Akun Orang Tua</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editParentForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editParentId" name="id">
                        <div class="mb-3">
                            <label for="editName" class="form-label">Nama Orang Tua</label>
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
                            <label for="editStudent" class="form-label">Mahasiswa (Anak)</label>
                            <select class="form-select" id="editStudent" name="student_id" required>
                                <option value="">Pilih Mahasiswa</option>
                                @foreach ($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->user->name ?? $student->nim }}
                                        ({{ $student->nim }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editPhoneNumber" class="form-label">No. Telepon</label>
                            <input type="text" class="form-control" id="editPhoneNumber" name="phone_number">
                        </div>
                        <div class="mb-3">
                            <label for="editAddress" class="form-label">Alamat</label>
                            <textarea class="form-control" id="editAddress" name="address" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailParentModal" tabindex="-1" aria-labelledby="detailParentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailParentModalLabel">Detail Akun Orang Tua</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Nama Orang Tua:</strong> <span id="detailParentName"></span></p>
                    <p><strong>Email:</strong> <span id="detailParentEmail"></span></p>
                    <p><strong>Mahasiswa (Anak):</strong> <span id="detailStudentLink"></span></p>
                    <p><strong>No. Telepon:</strong> <span id="detailPhoneNumber"></span></p>
                    <p><strong>Alamat:</strong> <span id="detailAddress"></span></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(id, name, email, studentId, phoneNumber, address) {
            document.getElementById('editParentId').value = id;
            document.getElementById('editName').value = name;
            document.getElementById('editEmail').value = email;
            document.getElementById('editStudent').value = studentId;
            document.getElementById('editPhoneNumber').value = phoneNumber;
            document.getElementById('editAddress').value = address;
            document.getElementById('editParentForm').action = '{{ route('manage-parents-mhs.update', '') }}/' + id;
            var myModal = new bootstrap.Modal(document.getElementById('editParentModal'));
            myModal.show();
        }

        function openDetailModal(name, email, phoneNumber, address, studentLink) {
            document.getElementById('detailParentName').textContent = name;
            document.getElementById('detailParentEmail').textContent = email;
            document.getElementById('detailPhoneNumber').textContent = phoneNumber;
            document.getElementById('detailAddress').textContent = address;
            document.getElementById('detailStudentLink').textContent = studentLink;
            var myModal = new bootstrap.Modal(document.getElementById('detailParentModal'));
            myModal.show();
        }
    </script>
@endsection
