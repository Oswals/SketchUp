@extends('auth.auth')

@section('title', 'Login')

@section('contents')
<section class="vh-100">
    <div class="container-fluid h-custom">
        <div class="row d-flex justify-content-center align-items-center h-100">

            <!-- Image Section - Visible uniquement sur DESKTOP -->
            {{--<div class="col-md-9 col-lg-6 col-xl-5 d-none d-md-block">
               <img src="{{ asset('frontend/assets/img/two.svg') }}" class="img-fluid hero-img" alt="Login SketchConvert" data-aos="zoom-out" data-aos-delay="300">
            </div>--}}


            <!-- Form Section -->
            <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">

                <!-- 🆕 Logo - Visible uniquement sur PC (centré) -->
                <div class="text-center mb-4 d-none d-md-block">
                    <img src="{{ asset('frontend/assets/img/3.png') }}" alt="Logo" width="570" height="120">
                </div>

                <!-- 🆕 Logo - Visible uniquement sur MOBILE (centré) -->
                <div class="text-center mb-4 d-md-none">
                    <img src="{{ asset('frontend/assets/img/3.png') }}" alt="Logo" width="870" height="60">
                </div>

                <!-- Back to home button -->
                <div class="mb-3">
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="ti ti-arrow-left me-1"></i> Back to home
                    </a>
                </div>





                <form id="login-form" method="POST" action="{{ route('login.submit') }}">
                    @csrf

                    <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start mb-4">
                        <h3 class="fw-bold mb-0">Login</h3>
                    </div>

                    <!-- Back to home button -->
                    <div class="mb-3">
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="ti ti-arrow-left me-1"></i> Back to home
                        </a>
                    </div>

                    <!-- Trust badge -->
                    <div class="login-trust-badge mb-4">
                        <span class="stars">★★★★★</span>
                        <strong>+7,500 users</strong> already trust DownConvert
                    </div>

                    <form id="login-form" method="POST" action="{{ route('login.submit') }}">

                    <!-- Success messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="ti ti-circle-check me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Warning messages -->
                    @if(session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="ti ti-alert-triangle me-2"></i>
                            {{ session('warning') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Email verification alert (dynamique) -->
                    <div id="email-alert"></div>

                    <!-- 🆕 Google Login Button -->
                    <div class="mb-4">
                        <a href="{{ route('google.redirect') }}"
                           class="btn btn-outline-dark btn-lg w-100 d-flex align-items-center justify-content-center"
                           style="border-radius: 12px; padding: 0.75rem;">
                            <svg width="20" height="20" viewBox="0 0 20 20" class="me-2">
                                <path fill="#4285F4" d="M19.6 10.23c0-.82-.1-1.42-.25-2.05H10v3.72h5.5c-.15.96-.74 2.31-2.04 3.22v2.45h3.16c1.89-1.73 2.98-4.3 2.98-7.34z"/>
                                <path fill="#34A853" d="M13.46 15.13c-.83.59-1.96 1-3.46 1-2.64 0-4.88-1.74-5.68-4.15H1.07v2.52C2.72 17.75 6.09 20 10 20c2.7 0 4.96-.89 6.62-2.42l-3.16-2.45z"/>
                                <path fill="#FBBC05" d="M3.99 10c0-.69.12-1.35.32-1.97V5.51H1.07A9.973 9.973 0 000 10c0 1.61.39 3.14 1.07 4.49l3.24-2.52c-.2-.62-.32-1.28-.32-1.97z"/>
                                <path fill="#EA4335" d="M10 3.88c1.88 0 3.13.81 3.85 1.48l2.84-2.76C14.96.99 12.7 0 10 0 6.09 0 2.72 2.25 1.07 5.51l3.24 2.52C5.12 5.62 7.36 3.88 10 3.88z"/>
                            </svg>
                            Continue with Google
                        </a>
                    </div>

                    <!-- Divider -->
                    <div class="divider d-flex align-items-center my-4">
                        <p class="text-center fw-bold mx-3 mb-0 text-muted">OR</p>
                    </div>

                    <!-- Email -->
                    <div class="form-outline mb-4">
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               class="form-control form-control-lg @error('email') is-invalid @enderror"
                               placeholder=" "
                               required />

                        <label class="form-label" for="email">Email address</label>

                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password field (toujours visible sur toutes les tailles d'écran) -->
                    <div class="form-outline mb-4">
                        <input type="password"
                               id="password"
                               class="form-control form-control-lg @error('password') is-invalid @enderror"
                               name="password"
                               placeholder=" "
                               required>
                        <label class="form-label" for="password">Password</label>
                        <button class="btn btn-outline-secondary position-absolute top-50 end-0 translate-middle-y me-2"
                                type="button"
                                id="togglePassword"
                                style="z-index: 10;">
                            <i class="ti ti-eye-off"></i>
                        </button>

                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Remember me -->
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" value="" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>

                    <!-- Submit button -->
                    <div class="text-center text-lg-start mt-4 pt-2">
                        <button type="submit"
                                class="btn btn-primary btn-lg"
                                style="padding-left: 2.5rem; padding-right: 2.5rem;">
                            Log in
                        </button>

                        <!-- Security reassurance -->
                        <div class="login-security-note mt-3">
                            <i class="ti ti-lock"></i>
                            Your data is encrypted and never shared with third parties
                        </div>

                        <!-- Forgot password link -->
                        <p class="small fw-bold mt-3 pt-1 mb-2">
                            <a href="{{ route('password.request') }}" class="link-danger">
                                <i class="ti ti-lock-question me-1"></i>
                                Forgot password?
                            </a>
                        </p>

                        <p class="small fw-bold mt-2 pt-1 mb-0">
                            Don't have an account yet?
                            <a href="{{ route('register') }}" class="link-primary">Create an account</a>
                        </p>
                    </div>

                    <!-- Trust footer -->
                    <div class="login-trust-footer mt-4">
                        <span><i class="ti ti-shield-check"></i> SSL Secured</span>
                        <span><i class="ti ti-file-x"></i> No spam, ever</span>
                        <span><i class="ti ti-headset"></i> 24/7 support</span>
                    </div>

                </form>
            </div>
        </div>
    </div>

</section>

<style>

    /* Divider style */
    .divider:before,
    .divider:after {
        content: "";
        flex: 1;
        height: 1px;
        background: #dee2e6;
    }

    /* Ajustement du logo mobile */
    @media (max-width: 767px) {
        .text-center img {
            max-width: 250px !important;
        }
    }

    /* ─── Trust badge (haut de page) ─── */
    .login-trust-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 18px;
        background: #f8f9ff;
        border: 1px solid #e0e4f5;
        border-radius: 50px;
        font-size: 0.85rem;
        color: #333;
    }

    .login-trust-badge .stars {
        color: #ffb400;
        letter-spacing: 1px;
    }

    /* ─── Note de sécurité sous le bouton ─── */
    .login-security-note {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        font-size: 0.8rem;
        color: #6c757d;
    }

    .login-security-note i {
        color: #28a745;
        font-size: 0.9rem;
    }

    @media (min-width: 992px) {
        .login-security-note {
            justify-content: flex-start;
        }
    }

    /* ─── Footer de confiance (bas de page) ─── */
    .login-trust-footer {
        display: flex;
        justify-content: center;
        gap: 18px;
        flex-wrap: wrap;
        padding-top: 16px;
        border-top: 1px solid #eee;
    }

    @media (min-width: 992px) {
        .login-trust-footer {
            justify-content: flex-start;
        }
    }

    .login-trust-footer span {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 0.78rem;
        color: #6c757d;
    }

    .login-trust-footer i {
        color: #28a745;
        font-size: 0.85rem;
    }

/* Divider style */
.divider:before,
.divider:after {
    content: "";
    flex: 1;
    height: 1px;
    background: #dee2e6;
}

/* Ajustement du logo mobile */
@media (max-width: 767px) {
    .text-center img {
        max-width: 250px !important;
    }
}
</style>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let checkTimeout = null;

    // Vérification de l'email avec debounce (informative uniquement - pas de blocking)
    $('#email').on('input', function () {
        clearTimeout(checkTimeout);
        const email = $(this).val().trim();

        checkTimeout = setTimeout(() => {
            checkEmailExists(email);
        }, 500);
    });

    // Function to display alerts
    function showAlert(type, message) {
        $('#email-alert').html(`
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <i class="ti ti-${type === 'danger' ? 'alert-circle' : type === 'warning' ? 'alert-triangle' : 'info-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `);

        setTimeout(function() {
            $('#email-alert .alert').fadeOut('slow', function() {
                $(this).remove();
            });
        }, 5000);
    }

    // Fonction de vérification de l'email (informative uniquement)
    function checkEmailExists(email) {
        if (!email || !email.includes('@')) {
            $('#email-alert').empty();
            return;
        }

        $.ajax({
            type: 'POST',
            url: '{{ route("check.user") }}',
            data: {
                email: email,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(response) {
                if (response.exists) {
                    // Vérifier le statut du compte
                    if (response.status === 2) {
                        showAlert('warning', 'Your account is pending validation by an administrator.');
                    } else if (response.status === 0) {
                        showAlert('danger', 'Your account has been disabled. Please contact the administrator.');
                    } else {
                        // Compte actif - effacer les alertes
                        $('#email-alert').empty();
                    }
                } else {
                    showAlert('danger', 'No account found with this email address.');
                }
            },
            error: function() {
                // En cas d'erreur, on n'affiche rien pour ne pas gêner l'utilisateur
                $('#email-alert').empty();
            }
        });
    }

    // Toggle password visibility
    $('#togglePassword').on('click', function() {
        const passwordField = $('#password');
        const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
        passwordField.attr('type', type);
        $(this).find('i').toggleClass('ti-eye-off ti-eye');
    });

    // Form submission with loading state
    $('#login-form').on('submit', function() {
        $(this).find('button[type="submit"]')
            .prop('disabled', true)
            .html('<span class="spinner-border spinner-border-sm me-2"></span>Logging in...');
    });

    // Vérification initiale si l'email est pré-rempli
    const initialEmail = $('#email').val();
    if (initialEmail) {
        checkEmailExists(initialEmail);
    }
});
</script>
@endpush
