@extends('admin.admin_master')

@section('content')

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">👤 Profil Utilisateur</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Utilisateurs</a></li>
                        <li class="breadcrumb-item active">{{ $user->username }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            <div class="row">
                <!-- Colonne Gauche: Infos Utilisateur -->
                <div class="col-md-4">
                    <!-- Card Profil -->
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                @if($user->avatar)
                                    <img class="profile-user-img img-fluid img-circle" src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar">
                                @else
                                    <div class="bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px; font-size: 48px;">
                                        {{ strtoupper(substr($user->username, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <h3 class="profile-username text-center mt-3">{{ $user->username }}</h3>
                            <p class="text-muted text-center">{{ $user->email }}</p>

                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Rôle</b> 
                                    <span class="float-end">
                                        @if($user->role === 'admin')
                                            <span class="badge bg-danger">👑 Admin</span>
                                        @else
                                            <span class="badge bg-secondary">User</span>
                                        @endif
                                    </span>
                                </li>
                                <li class="list-group-item">
                                    <b>Statut</b> 
                                    <span class="float-end">
                                        @if($user->status == 1)
                                            <span class="badge bg-success">✅ Actif</span>
                                        @elseif($user->status == 2)
                                            <span class="badge bg-warning">⏳ En attente</span>
                                        @else
                                            <span class="badge bg-danger">❌ Désactivé</span>
                                        @endif
                                    </span>
                                </li>
                                <li class="list-group-item">
                                    <b>Solde Coins</b> 
                                    <span class="float-end">
                                        <span class="badge bg-primary">🪙 {{ $user->coin_balance }}</span>
                                    </span>
                                </li>
                                <li class="list-group-item">
                                    <b>Inscription</b> 
                                    <span class="float-end">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                                </li>
                            </ul>

                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                                    <i class="bi bi-pencil"></i> Éditer
                                </a>
                                @if($user->status != 1)
                                    <form action="{{ route('admin.users.activate', $user) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="bi bi-check-circle"></i> Activer
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.users.deactivate', $user) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-secondary w-100">
                                            <i class="bi bi-x-circle"></i> Désactiver
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Card Ajouter Coins -->
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">🪙 Gérer les Coins</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.users.addCoins', $user) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Nombre de Coins</label>
                                    <input type="number" name="coins" class="form-control" placeholder="Ex: 10 (positif) ou -5 (négatif)" required>
                                    <small class="text-muted">Utilisez un nombre négatif pour retirer des coins</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <input type="text" name="description" class="form-control" placeholder="Ex: Bonus fidélité">
                                </div>
                                <button type="submit" class="btn btn-warning w-100">
                                    <i class="bi bi-plus-circle"></i> Appliquer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Colonne Droite: Statistiques & Historiques -->
                <div class="col-md-8">
                    <!-- Stats Cards -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="bi bi-arrow-repeat"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Conversions</span>
                                    <span class="info-box-number">{{ $userStats['total_conversions'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="bi bi-check-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Réussies</span>
                                    <span class="info-box-number">{{ $userStats['completed_conversions'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="bi bi-x-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Échouées</span>
                                    <span class="info-box-number">{{ $userStats['failed_conversions'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="bi bi-coin"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Coins Utilisés</span>
                                    <span class="info-box-number">{{ $userStats['total_coins_used'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Historique Conversions -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">📂 Historique des Conversions</h3>
                        </div>
                        <div class="card-body p-0">
                            @if($user->conversions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Fichier</th>
                                            <th>Version</th>
                                            <th>Statut</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($user->conversions as $conversion)
                                        <tr>
                                            <td>#{{ $conversion->id }}</td>
                                            <td>{{ Str::limit($conversion->original_filename, 30) }}</td>
                                            <td>
                                                <small>{{ $conversion->source_version }} → {{ $conversion->target_version }}</small>
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
                                            <td>{{ $conversion->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <a href="{{ route('admin.conversions.show', $conversion) }}" class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <p class="text-center p-3">Aucune conversion effectuée</p>
                            @endif
                        </div>
                    </div>

                    <!-- Historique Transactions Coins -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">💰 Historique des Transactions Coins</h3>
                        </div>
                        <div class="card-body p-0">
                            @if($user->coinTransactions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Coins</th>
                                            <th>Solde Après</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($user->coinTransactions as $transaction)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y H:i') }}</td>

                                            <td>
                                                @if($transaction->type == 'credit')
                                                    <span class="badge bg-success">➕ Crédit</span>
                                                @else
                                                    <span class="badge bg-danger">➖ Débit</span>
                                                @endif
                                            </td>
                                            <td>
                                                <strong class="{{ $transaction->type == 'credit' ? 'text-success' : 'text-danger' }}">
                                                    {{ $transaction->type == 'credit' ? '+' : '-' }}{{ $transaction->coins }}
                                                </strong>
                                            </td>
                                            <td>{{ $transaction->balance_after }}</td>
                                            <td>{{ $transaction->description }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <p class="text-center p-3">Aucune transaction</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

@endsection