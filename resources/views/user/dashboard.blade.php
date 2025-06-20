@extends('layouts.main')

@section('title', 'Dashboard Mahasiswa')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Dashboard Mahasiswa</h4>
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
                                        <h5 class="card-title">Informasi Profil</h5>
                                        <p class="card-text">
                                            @if ($user->name)
                                                <strong>Nama:</strong> {{ $user->name }}<br>
                                            @endif
                                            @if ($user->email)
                                                <strong>Email:</strong> {{ $user->email }}<br>
                                            @endif
                                            @if ($student)
                                                @if ($student->nim)
                                                    <strong>NIM:</strong> {{ $student->nim }}<br>
                                                @endif
                                                @if ($student->department)
                                                    <strong>Jurusan:</strong> {{ $student->department->name }}<br>
                                                @endif
                                                @if ($student->gpa !== null)
                                                    <strong>IPK:</strong> {{ number_format($student->gpa, 2) }}<br>
                                                @endif
                                            @endif
                                        </p>
                                        @if ($student)
                                            <a href="{{ route('profile-mhs.index') }}"
                                                class="btn btn-sm btn-primary">Lihat Profil Lengkap</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Ringkasan Absensi</h5>
                                        @if ($totalMeetings > 0)
                                            <p class="card-text">
                                                <strong>Total Pertemuan Tercatat:</strong> {{ $totalMeetings }}<br>
                                                <strong>Hadir:</strong> {{ $attendanceRecap->get('present', 0) }}<br>
                                                <strong>Absen:</strong> {{ $attendanceRecap->get('absent', 0) }}<br>
                                                <strong>Sakit:</strong> {{ $attendanceRecap->get('sick', 0) }}<br>
                                                <strong>Izin:</strong> {{ $attendanceRecap->get('permission', 0) }}<br>
                                                <strong>Persentase Kehadiran:</strong> {{ $percentagePresent }}%
                                            </p>
                                            <a href="{{ route('rekap-absensi.index') }}"
                                                class="btn btn-sm btn-primary">Lihat Rekap Absensi Lengkap</a>
                                        @else
                                            <p class="card-text">Belum ada data absensi yang tercatat.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h5>Grafik Absensi Bulanan</h5>
                        @if (count($labels) > 0)
                            <div class="chart-container"
                                style="position: relative; height:40vh; max-width: 100%; margin: auto;">
                                <canvas id="monthlyAttendanceChart"></canvas>
                            </div>
                        @else
                            <div class="alert alert-info mt-3">Tidak ada data absensi bulanan untuk ditampilkan.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    @if (count($labels) > 0)
        <script>
            const ctx = document.getElementById('monthlyAttendanceChart').getContext('2d');
            const monthlyAttendanceChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($labels),
                    datasets: [{
                            label: 'Hadir',
                            data: @json($presentData),
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 2,
                            fill: false,
                            tension: 0.3
                        },
                        {
                            label: 'Absen',
                            data: @json($absentData),
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 2,
                            fill: false,
                            tension: 0.3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Jumlah Pertemuan'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Bulan'
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        },
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    }
                }
            });
        </script>
    @endif
@endpush
