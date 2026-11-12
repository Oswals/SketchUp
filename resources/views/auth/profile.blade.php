@extends('frontend.layout.app')

@section('content')

<!-- Success Toast -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 11000;">
    <div id="successToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-check-circle-fill me-2"></i>
                <strong id="toastMessage">Profile updated successfully!</strong>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<!-- Profile Section -->
<section id="profile-section" class="section">
    <div class="container-fluid">
        <div class="row">

            <!-- Left Sidebar -->
            <div class="col-lg-3 col-xl-2 order-2 order-lg-1">
                <div class="sidebar-container" style="position: sticky; top: 100px;">

                    <!-- Profile Card -->
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body text-center py-4">
                            <div class="position-relative d-inline-block mb-3">
                                <div class="avatar-wrapper">
                                        <div class="avatar-placeholder rounded-circle d-flex align-items-center justify-content-center"
                                             style="width: 100px; height: 100px; background: #3150bc;">
                                            <span class="text-white fs-1 fw-bold">
                                                {{ strtoupper(substr($user->username, 0, 1)) }}
                                            </span>
                                        </div>
                                </div>
                                <span class="position-absolute bottom-0 end-0 p-2 bg-success border border-white rounded-circle"
                                      title="Active"></span>
                            </div>
                            <h5 class="mb-1 fw-bold">{{ $user->username }}</h5>
                            <p class="text-muted small mb-3">{{ $user->email }}</p>
                            <span class="badge bg-primary-gradient px-3 py-2">
                                <i class="bi bi-shield-check me-1"></i>
                                {{ $user->isActive() ? 'Active' : 'Pending' }} Account
                            </span>
                            <a href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                class="badge bg-danger px-3 py-2 cursor-pointer">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    Log Out
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>

                    <!-- Stats Card -->
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body stats-gradient" style="border-radius: 10px;">
                            <h6 class="mb-3 fw-semibold" style="color: white;">Statistics</h6>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Conversions</span>
                                <strong>{{ $conversions->count() }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Downloads</span>
                                <strong>{{ $conversions->where('downloaded', true)->count() }}</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Member since</span>
                                <strong>{{ $user->created_at->format('M Y') }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">Navigation</h6>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <a href="#profile-info" class="nav-link-custom">
                                        <i class="bi bi-person-circle me-3 text-primary fs-5"></i>
                                        <span>Information</span>
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="#security-section" class="nav-link-custom">
                                        <i class="bi bi-shield-lock me-3 text-primary fs-5"></i>
                                        <span>Security</span>
                                    </a>
                                </li>
                               <li class="mb-2">
                                    <a href="#" class="nav-link-custom"
                                    data-bs-toggle="modal" data-bs-target="#conversionModal">
                                        <i class="bi bi-plus-circle me-3 text-primary fs-5"></i>
                                        <span>New Conversion</span>
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="{{ route('home') }}" class="nav-link-custom">
                                        <i class="bi bi-house-door me-3 text-primary fs-5"></i>
                                        <span>Home</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9 col-xl-10 order-1 order-lg-2">

                <!-- Section Title -->
                <div class="section-title mb-4">
                    <h2 class="mb-2">My Profile</h2>
                    <p class="text-muted">Manage your personal information and security settings</p>
                </div>

                <!-- Alert Messages -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Errors detected:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <!-- Profile Information -->
                <div class="card shadow-sm border-0 mb-4" id="profile-info">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="mb-0 d-flex align-items-center">
                            <i class="bi bi-person-circle me-2 text-primary"></i>
                            Personal Information
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
                            @csrf
                            @method('PUT')

                            <div class="row g-4">
                                <!-- Profile Photo -->
                                <div class="col-12">
                                    <div class="text-center mb-4">
                                        <div class="position-relative d-inline-block">
                                            <div id="avatarPreview" class="avatar-preview-large">
                                                <div class="avatar-placeholder rounded-circle d-flex align-items-center justify-content-center"
                                                    style="width: 150px; height: 150px; background: #3150bc;">
                                                    <span class="text-white display-4 fw-bold">
                                                        {{ strtoupper(substr($user->username, 0, 1)) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Username -->
                                <div class="col-md-6">
                                    <label for="username" class="form-label fw-bold">
                                        <i class="bi bi-person me-2 text-primary"></i>
                                        Username
                                    </label>
                                    <input type="text"
                                        class="form-control form-control-lg @error('username') is-invalid @enderror"
                                        id="username"
                                        name="username"
                                        value="{{ old('username', $user->username) }}"
                                        required>
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-bold">
                                        <i class="bi bi-envelope me-2 text-primary"></i>
                                        Email Address
                                    </label>
                                    <input type="email"
                                        class="form-control form-control-lg @error('email') is-invalid @enderror"
                                        id="email"
                                        name="email"
                                        value="{{ old('email', $user->email) }}"
                                        required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Submit Button -->
                                <div class="col-12">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary btn-lg px-5">
                                            <i class="bi bi-check-circle me-2"></i>
                                            Save Changes
                                        </button>
                                        <button type="reset" class="btn btn-outline-secondary btn-lg">
                                            <i class="bi bi-arrow-counterclockwise me-2"></i>
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Security -->
                <div class="card shadow-sm border-0 mb-4" id="security-section">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="mb-0 d-flex align-items-center">
                            <i class="bi bi-shield-lock me-2 text-primary"></i>
                            Account Security
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('profile.password') }}" method="POST" id="passwordForm">
                            @csrf
                            @method('PUT')

                            <div class="row g-4">
                                <!-- Current Password -->
                                <div class="col-12">
                                    <label for="current_password" class="form-label fw-bold">
                                        <i class="bi bi-lock me-2 text-primary"></i>
                                        Current Password
                                    </label>
                                    <div class="input-group">
                                        <input type="password"
                                               class="form-control form-control-lg @error('current_password') is-invalid @enderror"
                                               id="current_password"
                                               name="current_password"
                                               required>
                                        <button class="btn btn-outline-secondary" type="button" id="toggleCurrentPassword">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        @error('current_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- New Password -->
                                <div class="col-md-6">
                                    <label for="new_password" class="form-label fw-bold">
                                        <i class="bi bi-key me-2 text-primary"></i>
                                        New Password
                                    </label>
                                    <div class="input-group">
                                        <input type="password"
                                               class="form-control form-control-lg @error('new_password') is-invalid @enderror"
                                               id="new_password"
                                               name="new_password"
                                               required>
                                        <button class="btn btn-outline-secondary" type="button" id="toggleNewPassword">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        @error('new_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Minimum 8 characters
                                    </small>
                                </div>

                                <!-- Confirmation -->
                                <div class="col-md-6">
                                    <label for="new_password_confirmation" class="form-label fw-bold">
                                        <i class="bi bi-shield-check me-2 text-primary"></i>
                                        Confirm Password
                                    </label>
                                    <div class="input-group">
                                        <input type="password"
                                               class="form-control form-control-lg"
                                               id="new_password_confirmation"
                                               name="new_password_confirmation"
                                               required>
                                        <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-lg px-5">
                                        <i class="bi bi-shield-check me-2"></i>
                                        Change Password
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                 <!-- Quick Stats -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-files fs-1" style="color: #1565c0;"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h3 class="mb-0">{{ $conversions->count() }}</h3>
                                        <p class="text-muted mb-0 small">Successful conversions</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-hdd fs-1 text-success"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h3 class="mb-0">{{ round($conversions->sum(fn($c) => $c->converted_size) / 1024 / 1024, 2) }} MB</h3>
                                        <p class="text-muted mb-0 small">Space used</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-clock-history fs-1 text-warning"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h3 class="mb-0">{{ round($conversions->avg('conversion_duration') ?? 0, 2) }}s</h3>
                                        <p class="text-muted mb-0 small">Average time</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- My Conversions -->
                <div class="card shadow-sm border-0 mb-4" id="conversions-section">
                    <div class="card-header bg-white py-3 border-bottom">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="mb-0 d-flex align-items-center">
                                    <i class="bi bi-file-earmark-check me-2 text-primary"></i>
                                    My Recent Conversions ({{ $conversions->count() }})
                                </h5>
                            </div>
                           <div class="col-auto">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#conversionModal">
                                        <i class="bi bi-plus-circle me-1"></i>
                                        New Conversion
                                    </button>
                                    <a href="{{ route('conversion.result') }}" class="btn btn-outline-primary">
                                        <i class="bi bi-arrow-right me-1"></i>
                                        View All
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if($conversions->isEmpty())
                            <div class="text-center py-5">
                                <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                                <p class="text-muted mb-3">No conversions available</p>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#conversionModal">
                                    <i class="bi bi-plus-circle me-2"></i>
                                    New Conversion
                                    </button>
                            </div>
                        @else
                           <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>File</th>
                                            <th>Date</th>
                                            <th>Versions</th>
                                            <th>Size</th>
                                            <th>Status</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($conversions->take(5) as $conversion)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-file-earmark-text text-primary me-3 fs-4"></i>
                                                    <div>
                                                        <div class="fw-semibold">{{ Str::limit($conversion->original_filename, 25) }}</div>
                                                        <small class="text-muted">{{ $conversion->formatted_original_size }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>{{ $conversion->created_at->format('d/m/Y') }}</div>
                                                <small class="text-muted">{{ $conversion->created_at->format('H:i') }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-info bg-opacity-10 text-info">{{ $conversion->source_version }}</span>
                                                <i class="bi bi-arrow-right mx-1"></i>
                                                <span class="badge bg-success bg-opacity-10 text-success">{{ $conversion->target_version }}</span>
                                            </td>
                                            <td>
                                                <div class="fw-semibold">{{ $conversion->formatted_converted_size }}</div>
                                                <small class="text-muted">{{ $conversion->conversion_duration }}s</small>
                                            </td>
                                            <td>
                                                @if($conversion->downloaded)
                                                    <span class="badge bg-secondary">
                                                        <i class="bi bi-check-circle me-1"></i>Downloaded
                                                    </span>
                                                @else
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle-fill me-1"></i>Ready
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($conversion->isDownloadable())
                                                    <a href="{{ route('conversion.download', $conversion->id) }}"
                                                    class="btn btn-primary btn-sm"
                                                    title="Download">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                @else
                                                    <span class="btn btn-outline-secondary btn-sm disabled"
                                                        title="File expired after 3 days"
                                                        data-bs-toggle="tooltip">
                                                        <i class="bi bi-clock-history"></i>
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="card shadow-sm border-danger mb-4">
                    <div class="card-header bg-danger bg-opacity-10 py-3 border-danger">
                        <h5 class="mb-0 d-flex align-items-center text-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Danger Zone
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-1">Delete My Account</h6>
                                <p class="text-muted mb-0">Once deleted, your account cannot be recovered.</p>
                            </div>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                <i class="bi bi-trash me-2"></i>
                                Delete Account
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 bg-danger bg-opacity-10">
                <h5 class="modal-title text-danger" id="deleteAccountModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Confirm Deletion
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <div class="text-center mb-4">
                    <i class="bi bi-x-circle text-danger" style="font-size: 4rem;"></i>
                </div>
                <h5 class="text-center mb-3">Are you absolutely sure?</h5>
                <p class="text-muted text-center mb-4">
                    This action is <strong>irreversible</strong>. All your data, conversions and files will be permanently deleted.
                </p>
                <form action="{{ route('profile.delete') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label fw-bold">
                            Confirm with your password:
                        </label>
                        <input type="password"
                               class="form-control"
                               id="confirmPassword"
                               name="password"
                               placeholder="Enter your password"
                               required>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-danger btn-lg">
                            <i class="bi bi-trash me-2"></i>
                            Yes, permanently delete my account
                        </button>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Conversion Modal -->
@include('frontend.partials.conversion-modal')

@endsection

@push('styles')
<style>
    /* ===== HARMONISATION AVEC #3150bc ===== */
    :root {
        --primary-color: #3150bc;
        --primary-dark: #2a44a3;
        --primary-light: #4a65d4;
        --text-color: #2c3e50;
    }

    /* Global Styles */
    #profile-section {
        min-height: 80vh;
        padding-top: 220px;
    }

    /* Text Colors */
    .text-primary {
        color: var(--primary-color) !important;
    }

    /* ===== BUTTONS ===== */
    .btn-primary {
        background: var(--primary-color);
        border: none;
        color: white;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(49, 80, 188, 0.15);
    }

    .btn-primary:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(49, 80, 188, 0.25);
        color: white;
    }

    .btn-outline-primary {
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-outline-primary:hover {
        background-color: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    /* ===== BADGES ===== */
    .bg-primary-gradient {
        background: var(--primary-color) !important;
        color: white;
    }

    /* ===== STATS CARD ===== */
    .stats-gradient {
        background: var(--primary-color);
        color: white;
    }

    /* Sidebar Styles */
    .sidebar-container {
        padding-right: 15px;
    }

    .sidebar-container .card {
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .sidebar-container .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    .nav-link-custom {
        display: flex;
        align-items: center;
        text-decoration: none;
        color: var(--text-color);
        transition: all 0.3s ease;
        padding: 8px 10px;
        border-radius: 8px;
    }

    .nav-link-custom:hover {
        background-color: #f8f9fa;
        padding-left: 15px;
        color: var(--primary-color);
    }

    /* Avatar Styles */
    .avatar-wrapper,
    .avatar-preview-large {
        position: relative;
        transition: all 0.3s ease;
    }

    .avatar-wrapper:hover,
    .avatar-preview-large:hover {
        transform: scale(1.05);
    }

    /* Card Styles */
    .card {
        border-radius: 15px;
        overflow: hidden;
    }

    .card-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    }

    /* Form Styles */
    .form-control-lg {
        border-radius: 10px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .form-control-lg:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(49, 80, 188, 0.15);
    }

    /* Table Styles */
    .table thead th {
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem;
    }

    .table tbody td {
        padding: 1rem;
        vertical-align: middle;
    }

    .table tbody tr {
        transition: all 0.3s ease;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.001);
    }

    /* Badge Styles */
    .badge {
        font-weight: 500;
        font-size: 0.875rem;
        padding: 0.5em 0.8em;
    }

    /* Toast Styles */
    .toast {
        min-width: 350px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.2);
    }

    /* Section Title */
    .section-title h2 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-color);
    }

    /* Responsive */
    @media (max-width: 992px) {
        .sidebar-container {
            position: relative !important;
            top: auto !important;
            margin-bottom: 30px;
        }

        #profile-section {
            padding-top: 170px;
        }
    }

    /* Animation for smooth scroll */
    html {
        scroll-behavior: smooth;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Show toast if success
        @if(session('success'))
        const toastEl = document.getElementById('successToast');
        const toast = new bootstrap.Toast(toastEl, {
            autohide: true,
            delay: 5000
        });
        toast.show();
        @endif

        // Avatar preview
        $('#avatarInput').on('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#avatarPreview').html(`
                        <img src="${e.target.result}"
                             alt="Avatar Preview"
                             class="rounded-circle"
                             style="width: 150px; height: 150px; object-fit: cover;">
                    `);
                };
                reader.readAsDataURL(file);
            }
        });

        // Toggle password visibility
        $('#toggleCurrentPassword').on('click', function() {
            togglePasswordVisibility('#current_password', $(this));
        });

        $('#toggleNewPassword').on('click', function() {
            togglePasswordVisibility('#new_password', $(this));
        });

        $('#toggleConfirmPassword').on('click', function() {
            togglePasswordVisibility('#new_password_confirmation', $(this));
        });

        function togglePasswordVisibility(inputId, button) {
            const input = $(inputId);
            const icon = button.find('i');

            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('bi-eye').addClass('bi-eye-slash');
            } else {
                input.attr('type', 'password');
                icon.removeClass('bi-eye-slash').addClass('bi-eye');
            }
        }

        // Password form validation
        $('#passwordForm').on('submit', function(e) {
            const newPassword = $('#new_password').val();
            const confirmPassword = $('#new_password_confirmation').val();

            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('❌ Passwords do not match!');
                return false;
            }

            if (newPassword.length < 8) {
                e.preventDefault();
                alert('❌ Password must be at least 8 characters!');
                return false;
            }
        });

        // Smooth scroll for navigation links
        $('a[href^="#"]').on('click', function(e) {
            const href = $(this).attr('href');
            if (href !== '#' && !$(this).attr('data-bs-toggle')) {
                e.preventDefault();
                const target = $(href);
                if (target.length) {
                    $('html, body').stop().animate({
                        scrollTop: target.offset().top - 100
                    }, 800);
                }
            }
        });
    });
</script>
@endpush