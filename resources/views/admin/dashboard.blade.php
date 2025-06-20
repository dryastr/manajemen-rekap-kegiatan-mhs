@extends('layouts.main')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Dashboard Administrator</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Mahasiswa</h5>
                                        <p class="card-text fs-3">{{ $totalStudents }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Dosen</h5>
                                        <p class="card-text fs-3">{{ $totalLecturers }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Mata Kuliah</h5>
                                        <p class="card-text fs-3">{{ $totalCourses }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card bg-warning text-dark">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Fakultas</h5>
                                        <p class="card-text fs-3">{{ $totalFaculties }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card bg-secondary text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Jurusan</h5>
                                        <p class="card-text fs-3">{{ $totalDepartments }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card bg-dark text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Akun Pengguna</h5>
                                        <p class="card-text fs-3">{{ $totalUsers }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title">Ringkasan Absensi Sistem</h5>
                                        <p class="card-text">
                                            Total Data Absensi Tercatat: {{ $totalAttendances }}<br>
                                            Hadir: {{ $attendanceSummary->get('present', 0) }}
                                            ({{ $presentPercentage }}%)<br>
                                            Absen: {{ $attendanceSummary->get('absent', 0) }}
                                            ({{ $absentPercentage }}%)<br>
                                            Sakit: {{ $attendanceSummary->get('sick', 0) }}<br>
                                            Izin: {{ $attendanceSummary->get('permission', 0) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title">Statistik Mahasiswa per Jurusan</h5>
                                        <ul class="list-group list-group-flush">
                                            @foreach ($studentsPerDepartment as $department)
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-center">
                                                    {{ $department->name }}
                                                    <span
                                                        class="badge bg-primary rounded-pill">{{ $department->students_count }}
                                                        Mahasiswa</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title">Statistik Mata Kuliah per Jurusan</h5>
                                        <ul class="list-group list-group-flush">
                                            @foreach ($coursesPerDepartment as $department)
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-center">
                                                    {{ $department->name }}
                                                    <span
                                                        class="badge bg-info rounded-pill">{{ $department->courses_count }}
                                                        Mata Kuliah</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h5>Grafik Absensi Sistem Bulanan</h5>
                        @if (count($chartLabels) > 0)
                            <div class="chart-container"
                                style="position: relative; height:40vh; max-width: 100%; margin: auto;">
                                <canvas id="adminAttendanceChart"></canvas>
                            </div>
                        @else
                            <div class="alert alert-info mt-3">Tidak ada data absensi bulanan di seluruh sistem untuk
                                ditampilkan.</div>
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
    @if (count($chartLabels) > 0)
        <script>
            const adminCtx = document.getElementById('adminAttendanceChart').getContext('2d');
            const adminAttendanceChart = new Chart(adminCtx, {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                            label: 'Total Pertemuan',
                            data: @json($chartTotalRecords),
                            borderColor: 'rgba(153, 102, 255, 1)',
                            backgroundColor: 'rgba(153, 102, 255, 0.2)',
                            borderWidth: 2,
                            fill: false,
                            tension: 0.3
                        },
                        {
                            label: 'Hadir',
                            data: @json($chartPresentData),
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderWidth: 2,
                            fill: false,
                            tension: 0.3
                        },
                        {
                            label: 'Absen',
                            data: @json($chartAbsentData),
                            borderColor: 'rgba(255, 99, 132, 1)',
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
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
                                text: 'Jumlah Data'
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
