@extends('layouts.main')

@section('title', 'Dashboard Orang Tua')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Dashboard Orang Tua</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Informasi Anak</h5>
                                        <p class="card-text">
                                            <strong>Nama:</strong> {{ $student->user->name ?? 'N/A' }}<br>
                                            <strong>NIM:</strong> {{ $student->nim ?? 'N/A' }}<br>
                                            <strong>Jurusan:</strong> {{ $student->department->name ?? 'N/A' }}<br>
                                            <strong>IPK:</strong> {{ number_format($student->gpa ?? 0, 2) }}
                                        </p>
                                        <a href="{{ route('profile-mhs-parent.index') }}" class="btn btn-sm btn-primary">Lihat Profil Lengkap</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Ringkasan Absensi</h5>
                                        <p class="card-text">
                                            <strong>Total Pertemuan Tercatat:</strong> {{ $totalMeetings }}<br>
                                            <strong>Hadir:</strong> {{ $attendanceRecap->get('present', 0) }}<br>
                                            <strong>Absen:</strong> {{ $attendanceRecap->get('absent', 0) }}<br>
                                            <strong>Sakit:</strong> {{ $attendanceRecap->get('sick', 0) }}<br>
                                            <strong>Izin:</strong> {{ $attendanceRecap->get('permission', 0) }}<br>
                                            <strong>Persentase Kehadiran:</strong> {{ $percentagePresent }}%
                                        </p>
                                        <a href="{{ route('rekap-absensi-mhs.index') }}" class="btn btn-sm btn-primary">Lihat Rekap Absensi Lengkap</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row justify-content-center">
                            <div class="col-md-6 text-center">
                                <h5>Proporsi Status Absensi</h5>
                                @if($totalMeetings > 0)
                                    <img src="{{ $chartUrl }}" alt="Grafik Absensi" class="img-fluid">
                                @else
                                    <div class="alert alert-info mt-3">Tidak ada data absensi untuk ditampilkan dalam grafik.</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush
