<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sistem Informasi Manajemen Data Kegiatan Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
        }

        .navbar {
            box-shadow: 0 2px 4px rgba(0, 0, 0, .08);
        }

        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://media.studentcrowd.net/q90/content/university-images/queens-university-belfast-adobestock-525837958.jpeg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 80px 0;
            text-align: center;
            min-height: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-section .display-4 {
            font-weight: 700;
        }

        .stats-section .card {
            border: none;
            border-radius: 1rem;
            transition: all 0.3s ease-in-out;
        }

        .stats-section .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .15) !important;
        }

        .chart-section .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, .05);
        }

        .chart-container {
            position: relative;
            height: 300px;
            max-width: 100%;
            margin: auto;
        }

        .footer {
            background-color: #343a40;
            color: white;
            padding: 30px 0;
            text-align: center;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white py-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="{{ url('/') }}">Sistem Kegiatan MHS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="btn btn-outline-primary me-2" href="{{ route('login') }}">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="hero-section">
        <div class="container">
            <h1 class="display-4">Selamat Datang di Sistem Informasi Manajemen Data Kegiatan Mahasiswa</h1>
            <p class="lead">Platform terintegrasi untuk mengelola data akademik dan absensi mahasiswa.</p>
        </div>
    </header>

    <section class="stats-section py-5">
        <div class="container">
            <h2 class="text-center mb-5 fw-bold text-primary">Statistik Kampus</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center p-4">
                            <h3 class="display-5 fw-bold">{{ $totalStudents }}</h3>
                            <p class="lead">Mahasiswa</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center p-4">
                            <h3 class="display-5 fw-bold">{{ $totalFaculties }}</h3>
                            <p class="lead">Fakultas</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card bg-warning text-dark">
                        <div class="card-body text-center p-4">
                            <h3 class="display-5 fw-bold">{{ $totalDepartments }}</h3>
                            <p class="lead">Jurusan</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card bg-danger text-white">
                        <div class="card-body text-center p-4">
                            <h3 class="display-5 fw-bold">{{ $totalLecturers }}</h3>
                            <p class="lead">Dosen</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card bg-secondary text-white">
                        <div class="card-body text-center p-4">
                            <h3 class="display-5 fw-bold">{{ $totalCourses }}</h3>
                            <p class="lead">Mata Kuliah</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="chart-section py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5 fw-bold text-primary">Visualisasi Data</h2>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body p-4">
                            <h5 class="card-title text-center mb-4">Distribusi Status Absensi</h5>
                            @if ($totalAttendanceRecords > 0)
                                <div class="chart-container">
                                    <canvas id="attendanceDonutChart"></canvas>
                                </div>
                            @else
                                <div class="alert alert-info text-center mt-3">Tidak ada data absensi untuk grafik.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body p-4">
                            <h5 class="card-title text-center mb-4">Jumlah Mahasiswa per Jurusan</h5>
                            @if (count($studentsPerDepartmentData) > 0)
                                <div class="chart-container">
                                    <canvas id="studentsPerDepartmentChart"></canvas>
                                </div>
                            @else
                                <div class="alert alert-info text-center mt-3">Tidak ada data mahasiswa per jurusan
                                    untuk grafik.</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body p-4">
                            <h5 class="card-title text-center mb-4">Jumlah Mata Kuliah per Jurusan</h5>
                            @if (count($coursesPerDepartmentData) > 0)
                                <div class="chart-container" style="height: 400px;">
                                    <canvas id="coursesPerDepartmentChart"></canvas>
                                </div>
                            @else
                                <div class="alert alert-info text-center mt-3">Tidak ada data mata kuliah per jurusan
                                    untuk grafik.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5 fw-bold text-primary">Mata Kuliah Unggulan</h2>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body p-4">
                            @if ($sampleDepartment)
                                <h5 class="card-title mb-3">Daftar Mata Kuliah di Jurusan {{ $sampleDepartment->name }}
                                </h5>
                                <ul class="list-group list-group-flush">
                                    @foreach ($sampleCourses as $course)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>{{ $course->name }}</strong> ({{ $course->code }})
                                            </div>
                                            <span class="badge bg-primary rounded-pill">{{ $course->credits }}
                                                SKS</span>
                                        </li>
                                    @endforeach
                                    @if ($sampleCourses->isEmpty())
                                        <li class="list-group-item text-center text-muted">Tidak ada mata kuliah contoh
                                            di jurusan ini.</li>
                                    @endif
                                </ul>
                            @else
                                <div class="alert alert-info text-center">Tidak ada jurusan contoh untuk menampilkan
                                    daftar mata kuliah.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 Sistem Informasi Akademik. Semua Hak Dilindungi.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <script>
        if (document.getElementById('attendanceDonutChart')) {
            const donutCtx = document.getElementById('attendanceDonutChart').getContext('2d');
            const attendanceDonutChart = new Chart(donutCtx, {
                type: 'doughnut',
                data: {
                    labels: @json($attendanceChartData['labels']),
                    datasets: [{
                        data: @json($attendanceChartData['data']),
                        backgroundColor: @json($attendanceChartData['colors']),
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    const data = tooltipItem.dataset.data;
                                    const total = data.reduce((sum, value) => sum + value, 0);
                                    const currentValue = data[tooltipItem.dataIndex];
                                    const percentage = parseFloat((currentValue / total * 100).toFixed(2));
                                    return `${tooltipItem.label}: ${currentValue} (${percentage}%)`;
                                }
                            }
                        },
                        legend: {
                            position: 'bottom',
                        },
                        title: {
                            display: false
                        }
                    }
                }
            });
        }

        if (document.getElementById('studentsPerDepartmentChart')) {
            const studentsCtx = document.getElementById('studentsPerDepartmentChart').getContext('2d');
            const studentsPerDepartmentChart = new Chart(studentsCtx, {
                type: 'bar',
                data: {
                    labels: @json($studentsPerDepartmentLabels),
                    datasets: [{
                        label: 'Jumlah Mahasiswa',
                        data: @json($studentsPerDepartmentData),
                        backgroundColor: 'rgba(54, 162, 235, 0.8)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Jumlah'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }

        if (document.getElementById('coursesPerDepartmentChart')) {
            const coursesCtx = document.getElementById('coursesPerDepartmentChart').getContext('2d');
            const coursesPerDepartmentChart = new Chart(coursesCtx, {
                type: 'line',
                data: {
                    labels: @json($coursesPerDepartmentLabels),
                    datasets: [{
                        label: 'Jumlah Mata Kuliah',
                        data: @json($coursesPerDepartmentData),
                        borderColor: 'rgba(153, 102, 255, 1)',
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Jumlah'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }
    </script>
</body>

</html>
