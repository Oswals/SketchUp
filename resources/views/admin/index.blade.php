@extends('admin.admin_master')

@section('content')

<main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">📊 Dashboard Admin</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!--end::App Content Header-->

    <!--begin::App Content-->
    <div class="app-content">
        <div class="container-fluid">
            
            <!--begin::Stats Cards Row-->
            <div class="row">
                <!-- UTILISATEURS -->
                <div class="col-lg-3 col-6">
                    <div class="small-box text-bg-primary">
                        <div class="inner">
                            <h3>{{ $stats['total_users'] }}</h3>
                            <p>Utilisateurs Total</p>
                            <small>
                                ✅ {{ $stats['active_users'] }} actifs | 
                                ⏳ {{ $stats['pending_users'] }} en attente
                            </small>
                        </div>
                        <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z"></path>
                        </svg>
                        <a href="{{ route('admin.users.index') }}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                            Voir tous les users <i class="bi bi-link-45deg"></i>
                        </a>
                    </div>
                </div>

                <!-- CONVERSIONS -->
                <div class="col-lg-3 col-6">
                    <div class="small-box text-bg-success">
                        <div class="inner">
                            <h3>{{ $stats['total_conversions'] }}</h3>
                            <p>Conversions</p>
                            <small>
                                ✅ {{ $stats['conversions_completed'] }} réussies | 
                                ❌ {{ $stats['conversions_failed'] }} échouées
                            </small>
                        </div>
                        <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2.25 2.25a.75.75 0 000 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 00-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 000-1.5H5.378A2.25 2.25 0 017.5 15h11.218a.75.75 0 00.674-.421 60.358 60.358 0 002.96-7.228.75.75 0 00-.525-.965A60.864 60.864 0 005.68 4.509l-.232-.867A1.875 1.875 0 003.636 2.25H2.25zM3.75 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM16.5 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z"></path>
                        </svg>
                        <a href="{{ route('admin.conversions.index') }}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                            Voir conversions <i class="bi bi-link-45deg"></i>
                        </a>
                    </div>
                </div>

                <!-- TAUX DE RÉUSSITE -->
                <div class="col-lg-3 col-6">
                    <div class="small-box text-bg-warning">
                        <div class="inner">
                            <h3>{{ $stats['conversion_success_rate'] }}<sup class="fs-5">%</sup></h3>
                            <p>Taux de Réussite</p>
                            <small>
                                📈 {{ $stats['conversions_today'] }} conversions aujourd'hui
                            </small>
                        </div>
                        <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75zM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 01-1.875-1.875V8.625zM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 013 19.875v-6.75z"></path>
                        </svg>
                        <a href="#" class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover">
                            Statistiques <i class="bi bi-link-45deg"></i>
                        </a>
                    </div>
                </div>

                <!-- COINS EN CIRCULATION -->
                <div class="col-lg-3 col-6">
                    <div class="small-box text-bg-danger">
                        <div class="inner">
                            <h3>{{ number_format($stats['total_coins_distributed']) }}</h3>
                            <p>Coins en Circulation</p>
                            <small>
                                🪙 {{ number_format($stats['total_coins_used']) }} utilisés
                            </small>
                        </div>
                        <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" fill-rule="evenodd" d="M2.25 13.5a8.25 8.25 0 018.25-8.25.75.75 0 01.75.75v6.75H18a.75.75 0 01.75.75 8.25 8.25 0 01-16.5 0z"></path>
                            <path clip-rule="evenodd" fill-rule="evenodd" d="M12.75 3a.75.75 0 01.75-.75 8.25 8.25 0 018.25 8.25.75.75 0 01-.75.75h-7.5a.75.75 0 01-.75-.75V3z"></path>
                        </svg>
                        <a href="{{ route('admin.coins.transactions') }}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                            Gérer coins <i class="bi bi-link-45deg"></i>
                        </a>
                    </div>
                </div>
            </div>
            <!--end::Stats Cards Row-->

            <!--begin::Charts Row-->
            <div class="row">
                <!-- GRAPHIQUE CONVERSIONS -->
                <div class="col-lg-7">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title">📈 Conversions (7 derniers jours)</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="conversionsChart" style="height: 250px;"></canvas>
                        </div>
                    </div>

                    <!-- CONVERSIONS ÉCHOUÉES RÉCENTES -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title">🔴 Conversions Échouées Récentes</h3>
                        </div>
                        <div class="card-body p-0">
                            @if($recentFailedConversions->count() > 0)
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Utilisateur</th>
                                        <th>Fichier</th>
                                        <th>Erreur</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentFailedConversions as $conversion)
                                    <tr>
                                        <td>#{{ $conversion->id }}</td>
                                        <td>
                                            @if($conversion->user)
                                                <a href="{{ route('admin.users.show', $conversion->user->id) }}">
                                                    {{ $conversion->user->username }}
                                                </a>
                                            @else
                                                <em>Invité</em>
                                            @endif
                                        </td>
                                        <td>{{ Str::limit($conversion->original_filename, 30) }}</td>
                                        <td><small class="text-danger">{{ Str::limit($conversion->error_message, 40) }}</small></td>
                                        <td>{{ $conversion->created_at->diffForHumans() }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                            <p class="text-center p-3">✅ Aucune conversion échouée récemment</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- COLONNE DROITE -->
                <div class="col-lg-5">
                    <!-- GRAPHIQUE INSCRIPTIONS -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title">👥 Inscriptions (7 derniers jours)</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="usersChart" style="height: 150px;"></canvas>
                        </div>
                    </div>

                    <!-- TOP UTILISATEURS -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title">🏆 Top 5 Utilisateurs</h3>
                        </div>
                        <div class="card-body p-0">
                            @if($topUsers->count() > 0)
                            <table class="table table-sm">
                                <tbody>
                                    @foreach($topUsers as $user)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.users.show', $user->id) }}">
                                                {{ $user->username }}
                                            </a>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge bg-primary">{{ $user->conversions_count }} conversions</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                            <p class="text-center p-3">Aucune conversion effectuée</p>
                            @endif
                        </div>
                    </div>

                    <!-- UTILISATEURS EN ATTENTE -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title">⏳ Utilisateurs en Attente</h3>
                        </div>
                        <div class="card-body p-0">
                            @if($pendingUsers->count() > 0)
                            <table class="table table-sm">
                                <tbody>
                                    @foreach($pendingUsers as $user)
                                    <tr>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-primary">
                                                Valider
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                            <p class="text-center p-3">✅ Aucun utilisateur en attente</p>
                            @endif
                        </div>
                    </div>

                    <!-- VERSIONS POPULAIRES -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title">📊 Versions SketchUp Populaires</h3>
                        </div>
                        <div class="card-body">
                            @if($popularVersions->count() > 0)
                            <ul class="list-unstyled">
                                @foreach($popularVersions as $version)
                                <li class="mb-2">
                                    <strong>{{ $version->target_version }}</strong>
                                    <span class="float-end badge bg-secondary">{{ $version->count }} conversions</span>
                                </li>
                                @endforeach
                            </ul>
                            @else
                            <p class="text-center">Aucune donnée disponible</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Charts Row-->

            <!-- INFOS SUPPLÉMENTAIRES -->
            <div class="row">
                <div class="col-md-4">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="bi bi-clock"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Temps Moyen Conversion</span>
                            <span class="info-box-number">{{ round($avgConversionTime, 2) }}s</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="bi bi-file-earmark"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Poids Total Converti</span>
                            <span class="info-box-number">{{ round($totalSizeConverted, 2) }} MB</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="bi bi-calendar-week"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Conversions cette Semaine</span>
                            <span class="info-box-number">{{ $stats['conversions_week'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!--end::App Content-->
</main>

@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// GRAPHIQUE CONVERSIONS
const conversionsCtx = document.getElementById('conversionsChart').getContext('2d');
new Chart(conversionsCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($conversionsChart->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))) !!},
        datasets: [
            {
                label: 'Réussies',
                data: {!! json_encode($conversionsChart->pluck('completed')) !!},
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.4
            },
            {
                label: 'Échouées',
                data: {!! json_encode($conversionsChart->pluck('failed')) !!},
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                tension: 0.4
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'top' }
        }
    }
});

// GRAPHIQUE INSCRIPTIONS
const usersCtx = document.getElementById('usersChart').getContext('2d');
new Chart(usersCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($usersChart->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))) !!},
        datasets: [{
            label: 'Inscriptions',
            data: {!! json_encode($usersChart->pluck('total')) !!},
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgb(54, 162, 235)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        }
    }
});
</script>
@endpush