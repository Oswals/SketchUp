@extends('admin.admin_master')

@section('content')

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">✏️ Éditer Utilisateur</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Utilisateurs</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.show', $user) }}">{{ $user->username }}</a></li>
                        <li class="breadcrumb-item active">Éditer</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">📝 Formulaire d'édition</h3>
                        </div>

                        <form action="{{ route('admin.users.update', $user) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="card-body">
                                
                                <!-- Username -->
                                <div class="mb-3">
                                    <label for="username" class="form-label">
                                        Username <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           name="username" 
                                           id="username" 
                                           class="form-control @error('username') is-invalid @enderror" 
                                           value="{{ old('username', $user->username) }}" 
                                           required>
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="mb-3">
                                    <label for="email" class="form-label">
                                        Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" 
                                           name="email" 
                                           id="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email', $user->email) }}" 
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <!-- Statut -->
                                    <div class="col-md-4 mb-3">
                                        <label for="status" class="form-label">
                                            Statut <span class="text-danger">*</span>
                                        </label>
                                        <select name="status" 
                                                id="status" 
                                                class="form-select @error('status') is-invalid @enderror" 
                                                required>
                                            <option value="1" {{ old('status', $user->status) == 1 ? 'selected' : '' }}>
                                                ✅ Actif
                                            </option>
                                            <option value="2" {{ old('status', $user->status) == 2 ? 'selected' : '' }}>
                                                ⏳ En attente
                                            </option>
                                            <option value="0" {{ old('status', $user->status) == 0 ? 'selected' : '' }}>
                                                ❌ Désactivé
                                            </option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Rôle -->
                                    <div class="col-md-4 mb-3">
                                        <label for="role" class="form-label">
                                            Rôle <span class="text-danger">*</span>
                                        </label>
                                        <select name="role" 
                                                id="role" 
                                                class="form-select @error('role') is-invalid @enderror" 
                                                required>
                                            <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>
                                                User
                                            </option>
                                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                                👑 Admin
                                            </option>
                                        </select>
                                        @error('role')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Solde Coins -->
                                    <div class="col-md-4 mb-3">
                                        <label for="coin_balance" class="form-label">
                                            🪙 Solde Coins <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" 
                                               name="coin_balance" 
                                               id="coin_balance" 
                                               class="form-control @error('coin_balance') is-invalid @enderror" 
                                               value="{{ old('coin_balance', $user->coin_balance) }}" 
                                               min="0" 
                                               required>
                                        @error('coin_balance')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">
                                            Solde actuel: {{ $user->coin_balance }} coins
                                        </small>
                                    </div>
                                </div>

                                <hr>

                                <!-- Nouveau Mot de Passe (Optionnel) -->
                                <h5 class="mb-3">🔒 Changer le Mot de Passe (Optionnel)</h5>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label">Nouveau Mot de Passe</label>
                                        <input type="password" 
                                               name="password" 
                                               id="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               placeholder="Laisser vide pour ne pas changer">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Minimum 8 caractères</small>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="password_confirmation" class="form-label">Confirmer le Mot de Passe</label>
                                        <input type="password" 
                                               name="password_confirmation" 
                                               id="password_confirmation" 
                                               class="form-control" 
                                               placeholder="Confirmer le nouveau mot de passe">
                                    </div>
                                </div>

                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle"></i>
                                    <strong>Note:</strong> Si vous changez le mot de passe, l'utilisateur devra le réinitialiser à sa prochaine connexion.
                                </div>

                            </div>

                            <div class="card-footer">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left"></i> Annuler
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save"></i> Enregistrer les modifications
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Card Info Supplémentaire -->
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">ℹ️ Informations Complémentaires</h3>
                        </div>
                        <div class="card-body">
                            <ul class="mb-0">
                                <li><strong>ID:</strong> #{{ $user->id }}</li>
                                <li><strong>Date d'inscription:</strong> {{ $user->created_at->format('d/m/Y à H:i') }}</li>
                                <li><strong>Dernière modification:</strong> {{ $user->updated_at->format('d/m/Y à H:i') }}</li>
                                <li><strong>Total conversions:</strong> {{ $user->conversions()->count() }}</li>
                                @if($user->deleted_at)
                                    <li class="text-danger"><strong>⚠️ Compte supprimé le:</strong> {{ $user->deleted_at->format('d/m/Y à H:i') }}</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

@endsection

@push('script')
<script>
// Validation côté client
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    
    confirmInput.addEventListener('input', function() {
        if (passwordInput.value !== confirmInput.value) {
            confirmInput.setCustomValidity('Les mots de passe ne correspondent pas');
        } else {
            confirmInput.setCustomValidity('');
        }
    });
});
</script>
@endpush