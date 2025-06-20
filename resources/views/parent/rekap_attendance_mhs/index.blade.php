@extends('layouts.main')

@section('title', 'Rekap Absensi Anak')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Rekap Absensi Anak: {{ $student->user->name ?? 'N/A' }} (NIM:
                        {{ $student->nim ?? 'N/A' }})</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <div class="mb-4">
                            <h5>Ringkasan Absensi per Mata Kuliah</h5>
                            <div class="list-group">
                                @foreach ($formattedRecap as $courseId => $recapData)
                                    <div
                                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $recapData['course_name'] }}
                                                ({{ $recapData['course_code'] }})</strong>
                                            <small class="d-block text-muted">Total Pertemuan:
                                                {{ $recapData['total_meetings'] }}</small>
                                            <small class="d-block text-muted">Hadir: {{ $recapData['present'] }}
                                                ({{ $recapData['percentage_present'] }}%)</small>
                                            <small class="d-block text-muted">Absen: {{ $recapData['absent'] }}</small>
                                            <small class="d-block text-muted">Sakit: {{ $recapData['sick'] }}</small>
                                            <small class="d-block text-muted">Izin: {{ $recapData['permission'] }}</small>
                                        </div>
                                    </div>
                                @endforeach
                                @if ($formattedRecap->isEmpty())
                                    <div class="alert alert-info mt-3">Belum ada data absensi untuk anak Anda yang tercatat.
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if ($attendances->isNotEmpty())
                            <hr class="my-4">
                            <h5>Detail Absensi untuk
                                @if ($selectedCourseId)
                                    {{ $coursesAttended[$selectedCourseId]->name ?? 'Mata Kuliah Tidak Dikenal' }}
                                @else
                                    Mata Kuliah Ini
                                @endif
                            </h5>
                            <div class="list-group">
                                @foreach ($attendances as $attendance)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>Tanggal:</strong> {{ $attendance->date->format('d M Y') }}
                                        </div>
                                        <span
                                            class="badge {{ $attendance->status == 'present'
                                                ? 'bg-success'
                                                : ($attendance->status == 'absent'
                                                    ? 'bg-danger'
                                                    : ($attendance->status == 'sick'
                                                        ? 'bg-warning'
                                                        : 'bg-info')) }}">
                                            {{ ucfirst($attendance->status) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            @if ($selectedCourseId)
                                <div class="alert alert-info mt-4">Tidak ada detail absensi untuk mata kuliah yang dipilih.
                                </div>
                            @endif
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
