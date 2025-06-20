@extends('layouts.main')

@section('title', 'Profil Mahasiswa')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Profil Mahasiswa</h4>
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        
                        <div class="profile-details">
                            <h5 class="mb-3">Informasi Akun</h5>
                            <div class="mb-2">
                                <strong>Nama Lengkap:</strong> {{ $user->name ?? 'N/A' }}
                            </div>
                            <div class="mb-2">
                                <strong>Email:</strong> {{ $user->email ?? 'N/A' }}
                            </div>
                            <div class="mb-2">
                                <strong>Bergabung Sejak:</strong> {{ $user->created_at ? $user->created_at->format('d M Y') : 'N/A' }}
                            </div>
                            <div class="mb-4">
                                <strong>Peran:</strong> {{ $user->role->name ?? 'N/A' }}
                            </div>

                            @if ($student)
                                <h5 class="mb-3">Informasi Mahasiswa</h5>
                                <div class="mb-2">
                                    <strong>NIM:</strong> {{ $student->nim ?? 'N/A' }}
                                </div>
                                <div class="mb-2">
                                    <strong>Jurusan:</strong> {{ $student->department->name ?? 'N/A' }}
                                </div>
                                <div class="mb-2">
                                    <strong>IPK:</strong> {{ number_format($student->gpa ?? 0, 2) }}
                                </div>
                            @else
                                <div class="alert alert-info mt-4">
                                    Data spesifik mahasiswa tidak tersedia untuk akun ini.
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
