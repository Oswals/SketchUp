@extends('admin.admin_master')

@section('content')

<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">⚙️ Paramètres Système</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Paramètres</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- PARAMÈTRES GÉNÉRAUX -->
                    <div class="col-lg-6">
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h4 class="card-title mb-0">
                                    <i class="bi bi-gear"></i> Paramètres Généraux
                                </h4>
                            </div>
                            <div class="card-body">
                                @if(isset($settings['general']))
                                    @foreach($settings['general'] as $setting)
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">
                                                {{ $setting->label }}
                                            </label>
                                            
                                            @if($setting->type === 'boolean')
                                                <div class="form-check form-switch">
                                                    <input 
                                                        class="form-check-input" 
                                                        type="checkbox" 
                                                        name="settings[{{ $setting->key }}]" 
                                                        id="{{ $setting->key }}"
                                                        value="1"
                                                        {{ $setting->value === 'true' || $setting->value === '1' ? 'checked' : '' }}
                                                    >
                                                    <label class="form-check-label" for="{{ $setting->key }}">
                                                        {{ $setting->description }}
                                                    </label>
                                                </div>
                                            @elseif($setting->type === 'integer')
                                                <input 
                                                    type="number" 
                                                    name="settings[{{ $setting->key }}]" 
                                                    class="form-control" 
                                                    value="{{ $setting->value }}"
                                                    min="0"
                                                >
                                                <small class="text-muted">{{ $setting->description }}</small>
                                            @else
                                                <input 
                                                    type="text" 
                                                    name="settings[{{ $setting->key }}]" 
                                                    class="form-control" 
                                                    value="{{ $setting->value }}"
                                                >
                                                <small class="text-muted">{{ $setting->description }}</small>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-muted">Aucun paramètre général défini</p>
                                @endif
                            </div>
                        </div>

                        <!-- PARAMÈTRES EMAIL -->
                        <div class="card mb-4">
                            <div class="card-header bg-info text-white">
                                <h4 class="card-title mb-0">
                                    <i class="bi bi-envelope"></i> Paramètres Email
                                </h4>
                            </div>
                            <div class="card-body">
                                @if(isset($settings['email']))
                                    @foreach($settings['email'] as $setting)
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">
                                                {{ $setting->label }}
                                            </label>
                                            
                                            @if($setting->type === 'boolean')
                                                <div class="form-check form-switch">
                                                    <input 
                                                        class="form-check-input" 
                                                        type="checkbox" 
                                                        name="settings[{{ $setting->key }}]" 
                                                        id="{{ $setting->key }}"
                                                        value="1"
                                                        {{ $setting->value === 'true' || $setting->value === '1' ? 'checked' : '' }}
                                                    >
                                                    <label class="form-check-label" for="{{ $setting->key }}">
                                                        {{ $setting->description }}
                                                    </label>
                                                </div>
                                            @else
                                                <input 
                                                    type="{{ $setting->type === 'integer' ? 'number' : 'text' }}" 
                                                    name="settings[{{ $setting->key }}]" 
                                                    class="form-control" 
                                                    value="{{ $setting->value }}"
                                                >
                                                <small class="text-muted">{{ $setting->description }}</small>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-muted">Aucun paramètre email défini</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- PARAMÈTRES DE CONVERSION -->
                    <div class="col-lg-6">
                        <div class="card mb-4">
                            <div class="card-header bg-success text-white">
                                <h4 class="card-title mb-0">
                                    <i class="bi bi-arrow-repeat"></i> Paramètres de Conversion
                                </h4>
                            </div>
                            <div class="card-body">
                                @if(isset($settings['conversion']))
                                    @foreach($settings['conversion'] as $setting)
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">
                                                {{ $setting->label }}
                                            </label>
                                            
                                            @if($setting->type === 'json' && $setting->key === 'supported_versions')
                                                @php
                                                    $versions = json_decode($setting->value, true) ?? [];
                                                @endphp
                                                <div class="row">
                                                    @foreach(['2024', '2023', '2022', '2021', '2020', '2019', '2018', '2017', '2016', '2015'] as $version)
                                                        <div class="col-6 col-md-4 mb-2">
                                                            <div class="form-check">
                                                                <input 
                                                                    class="form-check-input" 
                                                                    type="checkbox" 
                                                                    name="versions[]" 
                                                                    value="{{ $version }}"
                                                                    id="version_{{ $version }}"
                                                                    {{ in_array($version, $versions) ? 'checked' : '' }}
                                                                >
                                                                <label class="form-check-label" for="version_{{ $version }}">
                                                                    SketchUp {{ $version }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <small class="text-muted">{{ $setting->description }}</small>
                                            @elseif($setting->type === 'integer')
                                                <input 
                                                    type="number" 
                                                    name="settings[{{ $setting->key }}]" 
                                                    class="form-control" 
                                                    value="{{ $setting->value }}"
                                                    min="0"
                                                >
                                                <small class="text-muted">{{ $setting->description }}</small>
                                            @else
                                                <input 
                                                    type="text" 
                                                    name="settings[{{ $setting->key }}]" 
                                                    class="form-control" 
                                                    value="{{ $setting->value }}"
                                                >
                                                <small class="text-muted">{{ $setting->description }}</small>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-muted">Aucun paramètre de conversion défini</p>
                                @endif
                            </div>
                        </div>

                        <!-- INFORMATIONS SYSTÈME -->
                        <div class="card mb-4">
                            <div class="card-header bg-secondary text-white">
                                <h4 class="card-title mb-0">
                                    <i class="bi bi-info-circle"></i> Informations Système
                                </h4>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2">
                                        <strong>Version Laravel:</strong> 
                                        <span class="badge bg-primary">{{ app()->version() }}</span>
                                    </li>
                                    <li class="mb-2">
                                        <strong>Version PHP:</strong> 
                                        <span class="badge bg-success">{{ phpversion() }}</span>
                                    </li>
                                    <li class="mb-2">
                                        <strong>Environnement:</strong> 
                                        <span class="badge bg-{{ app()->environment() === 'production' ? 'danger' : 'warning' }}">
                                            {{ strtoupper(app()->environment()) }}
                                        </span>
                                    </li>
                                    <li class="mb-2">
                                        <strong>Stockage disponible:</strong> 
                                        <span class="badge bg-info">
                                            {{ round(disk_free_space('/') / (1024 * 1024 * 1024), 2) }} GB
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BOUTONS D'ACTION -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-check-circle"></i> Enregistrer les Modifications
                                </button>
                                
                                <button 
                                    type="button" 
                                    class="btn btn-outline-secondary btn-lg" 
                                    onclick="if(confirm('Êtes-vous sûr de vouloir réinitialiser tous les paramètres ?')) { window.location='{{ route('admin.settings.reset') }}'; }"
                                >
                                    <i class="bi bi-arrow-clockwise"></i> Réinitialiser
                                </button>
                            </div>

                            <div>
                                <small class="text-muted">
                                    Dernière modification: {{ $settings->flatten()->sortByDesc('updated_at')->first()?->updated_at?->diffForHumans() ?? 'Jamais' }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>
</main>

@endsection

@push('script')
<script>
// Gérer la sélection des versions
document.addEventListener('DOMContentLoaded', function() {
    const versionCheckboxes = document.querySelectorAll('input[name="versions[]"]');
    const form = document.querySelector('form');
    
    if (form && versionCheckboxes.length > 0) {
        form.addEventListener('submit', function(e) {
            // Créer un champ caché pour les versions
            const selectedVersions = Array.from(versionCheckboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);
            
            // Créer un input hidden avec le JSON
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'settings[supported_versions]';
            hiddenInput.value = JSON.stringify(selectedVersions);
            
            form.appendChild(hiddenInput);
        });
    }
});
</script>
@endpush