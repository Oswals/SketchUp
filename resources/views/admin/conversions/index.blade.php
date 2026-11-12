@extends('admin.admin_master')

@section('content')

<main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">🔄 Gestion des Conversions</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Conversions</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!--begin::App Content-->
    <div class="app-content">
        <div class="container-fluid">

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-6">
                    <div class="small-box text-bg-info">
                        <div class="inner">
                            <h3>{{ $stats['total'] }}</h3>
                            <p>Total Conversions</p>
                        </div>
                        <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32l8.4-8.4z"></path>
                            <path d="M5.25 5.25a3 3 0 00-3 3v10.5a3 3 0 003 3h10.5a3 3 0 003-3V13.5a.75.75 0 00-1.5 0v5.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5V8.25a1.5 1.5 0 011.5-1.5h5.25a.75.75 0 000-1.5H5.25z"></path>
                        </svg>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box text-bg-success">
                        <div class="inner">
                            <h3>{{ $stats['completed'] }}</h3>
                            <p>Réussies</p>
                            <small>{{ $stats['success_rate'] }}% de réussite</small>
                        </div>
                        <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box text-bg-danger">
                        <div class="inner">
                            <h3>{{ $stats['failed'] }}</h3>
                            <p>Échouées</p>
                            <small>{{ $stats['processing'] }} en cours</small>
                        </div>
                        <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-1.72 6.97a.75.75 0 10-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 101.06 1.06L12 13.06l1.72 1.72a.75.75 0 101.06-1.06L13.06 12l1.72-1.72a.75.75 0 10-1.06-1.06L12 10.94l-1.72-1.72z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box text-bg-warning">
                        <div class="inner">
                            <h3>{{ round($stats['avg_duration'], 2) }}s</h3>
                            <p>Durée Moyenne</p>
                            <small>{{ $stats['total_size_mb'] }} MB convertis</small>
                        </div>
                        <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 6a.75.75 0 00-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 000-1.5h-3.75V6z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Versions Populaires -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">📊 Versions SketchUp les plus converties</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($popularVersions as $version)
                                <div class="col-md-2">
                                    <div class="text-center">
                                        <h4 class="mb-0">{{ $version->count }}</h4>
                                        <small class="text-muted">SketchUp {{ $version->target_version }}</small>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtres -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">🔍 Filtres</h3>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.conversions.index') }}">
                        <div class="row g-3">
                            <div class="col-md-2">
                                <label class="form-label">Statut</label>
                                <select name="status" class="form-select">
                                    <option value="">Tous</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Réussies</option>
                                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Échouées</option>
                                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>En cours</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Version Source</label>
                                <select name="source_version" class="form-select">
                                    <option value="">Toutes</option>
                                    @for($i = 2024; $i >= 2015; $i--)
                                        <option value="{{ $i }}" {{ request('source_version') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Version Cible</label>
                                <select name="target_version" class="form-select">
                                    <option value="">Toutes</option>
                                    @for($i = 2024; $i >= 2015; $i--)
                                        <option value="{{ $i }}" {{ request('target_version') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Téléchargé</label>
                                <select name="downloaded" class="form-select">
                                    <option value="">Tous</option>
                                    <option value="yes" {{ request('downloaded') == 'yes' ? 'selected' : '' }}>Oui</option>
                                    <option value="no" {{ request('downloaded') == 'no' ? 'selected' : '' }}>Non</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Date Début</label>
                                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Date Fin</label>
                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                            </div>
                        </div>

                        <div class="mt-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search"></i> Filtrer
                            </button>
                            <a href="{{ route('admin.conversions.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table des conversions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">📋 Liste des Conversions ({{ $conversions->total() }})</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Utilisateur</th>
                                    <th>Fichier</th>
                                    <th>Version</th>
                                    <th>Taille</th>
                                    <th>Statut</th>
                                    <th>Durée</th>
                                    <th>Téléchargé</th>
                                    <th>Date</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($conversions as $conversion)
                                <tr>
                                    <td><strong>#{{ $conversion->id }}</strong></td>
                                    <td>
                                        @if($conversion->user)
                                            <a href="{{ route('admin.users.show', $conversion->user) }}">
                                                {{ $conversion->user->username }}
                                            </a>
                                        @else
                                            <em class="text-muted">Invité</em>
                                        @endif
                                    </td>
                                    <td>
                                        <span title="{{ $conversion->original_filename }}">
                                            {{ Str::limit($conversion->original_filename, 25) }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>
                                            <span class="badge bg-secondary">{{ $conversion->source_version }}</span>
                                            →
                                            <span class="badge bg-primary">{{ $conversion->target_version }}</span>
                                        </small>
                                    </td>
                                    <td>
                                        <small>
                                            {{ round($conversion->original_size / 1048576, 2) }} MB
                                            @if($conversion->converted_size)
                                                <br>→ {{ round($conversion->converted_size / 1048576, 2) }} MB
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        @if($conversion->status == 'completed')
                                            <span class="badge bg-success">✅ Réussi</span>
                                        @elseif($conversion->status == 'failed')
                                            <span class="badge bg-danger">❌ Échoué</span>
                                        @elseif($conversion->status == 'processing')
                                            <span class="badge bg-info">⏳ En cours</span>
                                        @else
                                            <span class="badge bg-warning">⏸️ En attente</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($conversion->conversion_duration)
                                            {{ round($conversion->conversion_duration, 2) }}s
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($conversion->downloaded)
                                            <span class="badge bg-success">✅</span>
                                        @else
                                            <span class="badge bg-secondary">❌</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $conversion->created_at->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.conversions.show', $conversion) }}" class="btn btn-info" title="Voir détails">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if($conversion->status == 'failed')
                                                <form action="{{ route('admin.conversions.retry', $conversion) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-warning" title="Relancer">
                                                        <i class="bi bi-arrow-clockwise"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('admin.conversions.destroy', $conversion) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette conversion ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" title="Supprimer">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4">
                                        <p class="mb-0">Aucune conversion trouvée</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($conversions->hasPages())
                <div class="card-footer clearfix">
                    {{ $conversions->links() }}
                </div>
                @endif
            </div>

        </div>
    </div>
</main>

@endsection