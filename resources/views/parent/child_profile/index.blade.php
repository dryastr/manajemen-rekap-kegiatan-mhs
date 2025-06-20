@extends('layouts.main')

@section('title', 'Profil Anak')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Profil Anak Anda</h4>
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-body">

                        <div class="profile-details">
                            <h5 class="mb-3">Informasi Mahasiswa (Anak)</h5>
                            <div class="mb-2">
                                <strong>Nama Lengkap:</strong> {{ $student->user->name ?? 'N/A' }}
                            </div>
                            <div class="mb-2">
                                <strong>NIM:</strong> {{ $student->nim ?? 'N/A' }}
                            </div>
                            <div class="mb-2">
                                <strong>Jurusan:</strong> {{ $student->department->name ?? 'N/A' }}
                            </div>
                            <div class="mb-2">
                                <strong>IPK:</strong> {{ number_format($student->gpa ?? 0, 2) }}
                            </div>
                            <div class="mb-2">
                                <strong>Email:</strong> {{ $student->user->email ?? 'N/A' }}
                            </div>
                            <div class="mb-4">
                                <strong>Bergabung Sejak:</strong>
                                {{ $student->created_at ? $student->created_at->format('d M Y') : 'N/A' }}
                            </div>

                            <h5 class="mb-3">Informasi Akun Orang Tua Anda</h5>
                            <div class="mb-2">
                                <strong>Nama Orang Tua:</strong> {{ $user->name ?? 'N/A' }}
                            </div>
                            <div class="mb-2">
                                <strong>Email Akun:</strong> {{ $user->email ?? 'N/A' }}
                            </div>
                            <div class="mb-2">
                                <strong>Peran Akun:</strong> {{ $user->role->name ?? 'N/A' }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
