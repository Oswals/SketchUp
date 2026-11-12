@extends('admin.admin_master')

@section('content')

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">📊 Statistiques & Analytics</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Analytics</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            <!-- Sélecteur de période -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.analytics.index') }}" class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Période d'analyse</label>
                            <select name="period" class="form-select" onchange="this.form.submit()">
                                <option value="7" {{ $period == 7 ? 'selected' : '' }}>7 derniers jours</option>
                                <option value="30" {{ $period == 30 ? 'selected' : '' }}>30 derniers jours</option>
                                <option value="90" {{ $period == 90 ? 'selected' : '' }}>90 derniers jours</option>
                                <option value="365" {{ $period == 365 ? 'selected' : '' }}>1 an</option>
                            </select>
                        </div>
                        <div class="col-md-9 text-end">
                            <a href="{{ route('admin.analytics.export.csv', ['period' => $period]) }}" class="btn btn-success">
                                <i class="bi bi-file-earmark-spreadsheet"></i> Export CSV
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- KPIs Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-6">
                    <div class="small-box text-bg-primary">
                        <div class="inner">
                            <h3>{{ number_format($stats['total_users']) }}</h3>
                            <p>Utilisateurs Total</p>
                            <small>+{{ $stats['new_users_period'] }} cette période</small>
                        </div>
                        <div class="icon">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box text-bg-success">
                        <div class="inner">
                            <h3>{{ number_format($stats['total_conversions']) }}</h3>
                            <p>Conversions Total</p>
                            <small>+{{ $stats['conversions_period'] }} cette période</small>
                        </div>
                        <div class="icon">
                            <i class="bi bi-arrow-repeat"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box text-bg-warning">
                        <div class="inner">
                            <h3>{{ $stats['success_rate'] }}%</h3>
                            <p>Taux de Réussite</p>
                            <small>{{ $stats['avg_conversion_time'] }}s moyen</small>
                        </div>
                        <div class="icon">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box text-bg-danger">
                        <div class="inner">
                            <h3>{{ number_format($stats['coins_in_circulation']) }}</h3>
                            <p>Coins en Circulation</p>
                            <small>{{ number_format($stats['total_coins_used']) }} utilisés</small>
                        </div>
                        <div class="icon">
                            <i class="bi bi-coin"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Graphiques principaux -->
            <div class="row">
                <!-- Conversions dans le temps -->
                <div class="col-lg-8 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-graph-up"></i> Évolution des Conversions</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="conversionsChart" height="100"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Répartition statuts users -->
                <div class="col-lg-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Statuts Utilisateurs</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="userStatusChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Deuxième ligne de graphiques -->
            <div class="row">
                <!-- Inscriptions -->
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-person-plus"></i> Nouvelles Inscriptions</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="usersChart" height="150"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Utilisation Coins -->
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-coin"></i> Utilisation des Coins</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="coinsChart" height="150"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Heures de pointe -->
            <div class="row">
                <div class="col-lg-12 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-clock"></i> Heures de Pointe (Conversions par heure)</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="peakHoursChart" height="60"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tableaux de données -->
            <div class="row">
                <!-- Top Utilisateurs -->
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-trophy"></i> Top 10 Utilisateurs</h5>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Rang</th>
                                        <th>Utilisateur</th>
                                        <th>Email</th>
                                        <th class="text-end">Conversions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topUsers as $index => $user)
                                        <tr>
                                            <td>
                                                <span class="badge bg-{{ $index < 3 ? 'warning' : 'secondary' }}">
                                                    {{ $index + 1 }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.users.show', $user) }}">
                                                    {{ $user->username }}
                                                </a>
                                            </td>
                                            <td><small class="text-muted">{{ $user->email }}</small></td>
                                            <td class="text-end">
                                                <span class="badge bg-success">{{ $user->conversions_count }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-3 text-muted">Aucune donnée</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Versions populaires -->
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Versions SketchUp Populaires</h5>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Version</th>
                                        <th class="text-end">Conversions</th>
                                        <th class="text-end">Temps Moyen</th>
                                        <th class="text-end">Taux Réussite</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($versionStats as $version)
                                        <tr>
                                            <td><strong>{{ $version->target_version }}</strong></td>
                                            <td class="text-end">{{ $version->count }}</td>
                                            <td class="text-end">{{ $version->avg_time }}s</td>
                                            <td class="text-end">
                                                @php
                                                    $rate = $successRateByVersion->firstWhere('target_version', $version->target_version)?->success_rate ?? 0;
                                                @endphp
                                                <span class="badge bg-{{ $rate >= 90 ? 'success' : ($rate >= 70 ? 'warning' : 'danger') }}">
                                                    {{ $rate }}%
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-3 text-muted">Aucune donnée</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Autres KPIs -->
            <div class="row">
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="bi bi-file-earmark"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Taille Totale Traitée</span>
                            <span class="info-box-number">{{ $stats['total_size_processed'] }} MB</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="bi bi-hdd"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Taille Moyenne Fichier</span>
                            <span class="info-box-number">{{ $stats['avg_file_size'] }} MB</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="bi bi-people-fill"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Users avec Conversions</span>
                            <span class="info-box-number">{{ $stats['users_with_conversions'] }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-danger"><i class="bi bi-gift"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Coins Distribués</span>
                            <span class="info-box-number">{{ number_format($stats['total_coins_distributed']) }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Conversions Chart
new Chart(document.getElementById('conversionsChart'), {
    type: 'line',
    data: {
        labels: {!! json_encode($conversionsChart->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))) !!},
        datasets: [
            {
                label: 'Total',
                data: {!! json_encode($conversionsChart->pluck('total')) !!},
                borderColor: 'rgb(54, 162, 235)',
                backgroundColor: 'rgba(54, 162, 235, 0.1)',
                tension: 0.4
            },
            {
                label: 'Réussies',
                data: {!! json_encode($conversionsChart->pluck('completed')) !!},
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                tension: 0.4
            },
            {
                label: 'Échouées',
                data: {!! json_encode($conversionsChart->pluck('failed')) !!},
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.1)',
                tension: 0.4
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'top' } }
    }
});

// Users Chart
new Chart(document.getElementById('usersChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($usersChart->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))) !!},
        datasets: [{
            label: 'Inscriptions',
            data: {!! json_encode($usersChart->pluck('total')) !!},
            backgroundColor: 'rgba(54, 162, 235, 0.7)',
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } }
    }
});

// Coins Chart
new Chart(document.getElementById('coinsChart'), {
    type: 'line',
    data: {
        labels: {!! json_encode($coinsChart->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))) !!},
        datasets: [
            {
                label: 'Crédités',
                data: {!! json_encode($coinsChart->pluck('credited')) !!},
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                fill: true
            },
            {
                label: 'Utilisés',
                data: {!! json_encode($coinsChart->pluck('debited')) !!},
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                fill: true
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'top' } }
    }
});

// Peak Hours Chart
new Chart(document.getElementById('peakHoursChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($peakHours->pluck('hour')->map(fn($h) => $h . 'h')) !!},
        datasets: [{
            label: 'Conversions',
            data: {!! json_encode($peakHours->pluck('count')) !!},
            backgroundColor: 'rgba(255, 159, 64, 0.7)',
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } }
    }
});

// User Status Distribution
new Chart(document.getElementById('userStatusChart'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($userStatusDistribution->keys()) !!},
        datasets: [{
            data: {!! json_encode($userStatusDistribution->values()) !!},
            backgroundColor: [
                'rgb(40, 167, 69)',   // Actifs (vert)
                'rgb(255, 193, 7)',   // En attente (jaune)
                'rgb(220, 53, 69)'    // Désactivés (rouge)
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});
</script>
@endpush