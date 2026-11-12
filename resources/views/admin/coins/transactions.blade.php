@extends('admin.admin_master')

@section('title', 'Transactions de Coins')

@section('content')
<div class="container-fluid">
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">💰 Transactions de Coins</h1>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Coins Distribués</h6>
                            <h3 class="mb-0 text-success">{{ number_format($stats['total_distributed']) }}</h3>
                        </div>
                        <div class="text-success">
                            <i class="fas fa-plus-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Coins Utilisés</h6>
                            <h3 class="mb-0 text-danger">{{ number_format($stats['total_used']) }}</h3>
                        </div>
                        <div class="text-danger">
                            <i class="fas fa-minus-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">En Circulation</h6>
                            <h3 class="mb-0 text-primary">{{ number_format($stats['total_in_circulation']) }}</h3>
                        </div>
                        <div class="text-primary">
                            <i class="fas fa-coins fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Moyenne / User</h6>
                            <h3 class="mb-0 text-info">{{ number_format($stats['average_per_user'], 1) }}</h3>
                        </div>
                        <div class="text-info">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.coins.transactions') }}" class="row g-3">
                
                <div class="col-md-2">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select">
                        <option value="">Tous</option>
                        <option value="credit" {{ request('type') == 'credit' ? 'selected' : '' }}>Crédit</option>
                        <option value="debit" {{ request('type') == 'debit' ? 'selected' : '' }}>Débit</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Utilisateur</label>
                    <select name="user_id" class="form-select">
                        <option value="">Tous</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->username }}
                            </option>
                        @endforeach
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

                <div class="col-md-2">
                    <label class="form-label">Recherche</label>
                    <input type="text" name="search" class="form-control" placeholder="Description..." value="{{ request('search') }}">
                </div>

                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i>
                    </button>
                </div>

                @if(request()->hasAny(['type', 'user_id', 'date_from', 'date_to', 'search']))
                    <div class="col-md-12">
                        <a href="{{ route('admin.coins.transactions') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-times"></i> Réinitialiser les filtres
                        </a>
                    </div>
                @endif

            </form>
        </div>
    </div>

    <!-- Tableau des transactions -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">📜 Liste des Transactions ({{ $transactions->total() }})</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Utilisateur</th>
                            <th>Type</th>
                            <th>Coins</th>
                            <th>Solde Après</th>
                            <th>Description</th>
                            <th>Conversion</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->id }}</td>
                                <td>
                                    @if($transaction->user)
                                        <a href="{{ route('admin.users.show', $transaction->user) }}" class="text-decoration-none">
                                            <strong>{{ $transaction->user->username }}</strong><br>
                                            <small class="text-muted">{{ $transaction->user->email }}</small>
                                        </a>
                                    @else
                                        <span class="text-muted">Utilisateur supprimé</span>
                                    @endif
                                </td>
                                <td>
                                    @if($transaction->type == 'credit')
                                        <span class="badge bg-success">
                                            <i class="fas fa-plus"></i> Crédit
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-minus"></i> Débit
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <strong class="{{ $transaction->type == 'credit' ? 'text-success' : 'text-danger' }}">
                                        {{ $transaction->type == 'credit' ? '+' : '' }}{{ $transaction->coins }}
                                    </strong>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $transaction->balance_after }}</span>
                                </td>
                                <td>{{ $transaction->description }}</td>
                                <td>
                                    @if($transaction->conversion)
                                        <a href="{{ route('admin.conversions.show', $transaction->conversion) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-file"></i> Voir
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">
                                       {{ \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y H:i') }}
                                    </small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Aucune transaction trouvée</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($transactions->hasPages())
            <div class="card-footer">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

</div>
@endsection