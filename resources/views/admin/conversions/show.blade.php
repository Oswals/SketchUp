@extends('admin.admin_master')

@section('content')

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">📄 Détails de la Conversion #{{ $conversion->id }}</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.conversions.index') }}">Conversions</a></li>
                        <li class="breadcrumb-item active">#{{ $conversion->id }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            <div class="row">
                <!-- Colonne Gauche: Infos Principales -->
                <div class="col-md-5">
                    <!-- Card Statut -->
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">📊 Statut de la Conversion</h3>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                @if($conversion->status == 'completed')
                                    <div class="display-4 text-success">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </div>
                                    <h4 class="text-success">Conversion Réussie</h4>
                                @elseif($conversion->status == 'failed')
                                    <div class="display-4 text-danger">
                                        <i class="bi bi-x-circle-fill"></i>
                                    </div>
                                    <h4 class="text-danger">Conversion Échouée</h4>
                                @elseif($conversion->status == 'processing')
                                    <div class="display-4 text-info">
                                        <i class="bi bi-arrow-repeat"></i>
                                    </div>
                                    <h4 class="text-info">En cours de traitement...</h4>
                                @else
                                    <div class="display-4 text-warning">
                                        <i class="bi bi-pause-circle-fill"></i>
                                    </div>
                                    <h4 class="text-warning">En attente</h4>
                                @endif
                            </div>

                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>ID Conversion</b>
                                    <span class="float-end"><strong>#{{ $conversion->id }}</strong></span>
                                </li>
                                <li class="list-group-item">
                                    <b>Date de création</b>
                                    <span class="float-end">{{ $conversion->created_at->format('d/m/Y à H:i:s') }}</span>
                                </li>
                                @if($conversion->conversion_duration)
                                <li class="list-group-item">
                                    <b>Durée</b>
                                    <span class="float-end">
                                        <span class="badge bg-info">{{ round($conversion->conversion_duration, 2) }} secondes</span>
                                    </span>
                                </li>
                                @endif
                                <li class="list-group-item">
                                    <b>Téléchargé</b>
                                    <span class="float-end">
                                        @if($conversion->downloaded)
                                            <span class="badge bg-success">✅ Oui</span>
                                            <br><small>{{ $conversion->downloaded_at->format('d/m/Y H:i') }}</small>
                                        @else
                                            <span class="badge bg-secondary">❌ Non</span>
                                        @endif
                                    </span>
                                </li>
                                @if($conversion->expires_at)
                                <li class="list-group-item">
                                    <b>Expire le</b>
                                    <span class="float-end">
                                        <span class="badge bg-warning">{{ $conversion->expires_at->format('d/m/Y H:i') }}</span>
                                    </span>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    <!-- Card Utilisateur -->
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">👤 Informations Utilisateur</h3>
                        </div>
                        <div class="card-body">
                            @if($conversion->user)
                                <div class="text-center mb-3">
                                    @if($conversion->user->avatar)
                                        <img src="{{ asset('storage/' . $conversion->user->avatar) }}" class="img-circle" width="80" height="80" alt="Avatar">
                                    @else
                                        <div class="bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 36px;">
                                            {{ strtoupper(substr($conversion->user->username, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <ul class="list-group list-group-unbordered">
                                    <li class="list-group-item">
                                        <b>Username</b>
                                        <span class="float-end">{{ $conversion->user->username }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Email</b>
                                        <span class="float-end">{{ $conversion->user->email }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Solde Coins</b>
                                        <span class="float-end">
                                            <span class="badge bg-primary">🪙 {{ $conversion->user->coin_balance }}</span>
                                        </span>
                                    </li>
                                </ul>
                                <div class="mt-3">
                                    <a href="{{ route('admin.users.show', $conversion->user) }}" class="btn btn-info w-100">
                                        <i class="bi bi-person"></i> Voir le profil
                                    </a>
                                </div>
                            @else
                                <div class="text-center text-muted">
                                    <i class="bi bi-person-x display-4"></i>
                                    <p class="mt-2">Utilisateur invité</p>
                                    @if($conversion->ip_address)
                                        <small>IP: {{ $conversion->ip_address }}</small>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">⚡ Actions</h3>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                            
                                @if($conversion->status == 'failed')
                                    <form action="{{ route('admin.conversions.retry', $conversion) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-warning w-100">
                                            <i class="bi bi-arrow-clockwise"></i> Relancer la conversion
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.conversions.destroy', $conversion) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette conversion ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="bi bi-trash"></i> Supprimer
                                    </button>
                                </form>

                                <a href="{{ route('admin.conversions.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Retour à la liste
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Colonne Droite: Détails Fichiers -->
                <div class="col-md-7">
                    <!-- Fichier Original -->
                    <div class="card">
                        <div class="card-header bg-secondary">
                            <h3 class="card-title">📁 Fichier Original</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <th style="width: 200px;">Nom du fichier:</th>
                                        <td><strong>{{ $conversion->original_filename }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>Taille:</th>
                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ round($conversion->original_size / 1048576, 2) }} MB
                                            </span>
                                            <small class="text-muted">({{ number_format($conversion->original_size) }} octets)</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Version SketchUp:</th>
                                        <td><span class="badge bg-dark">SketchUp {{ $conversion->source_version }}</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Fichier Converti -->
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h3 class="card-title">📄 Fichier Converti</h3>
                        </div>
                        <div class="card-body">
                            @if($conversion->status == 'completed')
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <th style="width: 200px;">Nom du fichier:</th>
                                            <td><strong>{{ $conversion->converted_filename }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>Taille:</th>
                                            <td>
                                                <span class="badge bg-primary">
                                                    {{ round($conversion->converted_size / 1048576, 2) }} MB
                                                </span>
                                                <small class="text-muted">({{ number_format($conversion->converted_size) }} octets)</small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Version SketchUp:</th>
                                            <td><span class="badge bg-success">SketchUp {{ $conversion->target_version }}</span></td>
                                        </tr>
                                        <tr>
                                            <th>Chemin du fichier:</th>
                                            <td><code>{{ $conversion->converted_file_path }}</code></td>
                                        </tr>
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info mb-0">
                                    <i class="bi bi-info-circle"></i> Le fichier converti n'est pas encore disponible
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Message d'Erreur -->
                    @if($conversion->status == 'failed' && $conversion->error_message)
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">❌ Message d'Erreur</h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-danger mb-0">
                                <strong>Erreur:</strong><br>
                                {{ $conversion->error_message }}
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Transaction Coin -->
                    @if($conversion->coinTransaction)
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">🪙 Transaction Coin</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <th style="width: 200px;">Type:</th>
                                        <td>
                                            <span class="badge bg-danger">➖ Débit</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Coins utilisés:</th>
                                        <td><strong class="text-danger">-{{ $conversion->coinTransaction->coins }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>Solde après:</th>
                                        <td>{{ $conversion->coinTransaction->balance_after }} coins</td>
                                    </tr>
                                    <tr>
                                        <th>Date:</th>
                                        <td>{{ \Carbon\Carbon::parse($conversion->coinTransaction->created_at)->format('d/m/Y à H:i:s') }}</td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- Métadonnées Techniques -->
                    <div class="card">
                        <div class="card-header bg-dark">
                            <h3 class="card-title">🔧 Métadonnées Techniques</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <th style="width: 200px;">Session ID:</th>
                                        <td><code>{{ $conversion->session_id ?? 'N/A' }}</code></td>
                                    </tr>
                                    <tr>
                                        <th>Adresse IP:</th>
                                        <td><code>{{ $conversion->ip_address ?? 'N/A' }}</code></td>
                                    </tr>
                                    <tr>
                                        <th>Date création:</th>
                                        <td>{{ $conversion->created_at->format('d/m/Y à H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Dernière MAJ:</th>
                                        <td>{{ $conversion->updated_at->format('d/m/Y à H:i:s') }}</td>
                                    </tr>
                                    @if($conversion->deleted_at)
                                    <tr>
                                        <th>Supprimé le:</th>
                                        <td class="text-danger">{{ $conversion->deleted_at->format('d/m/Y à H:i:s') }}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

@endsection