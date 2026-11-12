@extends('auth.auth')

@section('title', 'Registration')

@section('contents')
<section class="vh-100">
    <div class="container-fluid h-custom">
        <div class="row d-flex justify-content-center align-items-center h-100">

            {{--<!-- Image Section - Visible uniquement sur DESKTOP -->
            <div class="col-md-9 col-lg-6 col-xl-5 d-none d-md-block">
                <img src="{{ asset('frontend/assets/img/two.svg') }}" class="img-fluid hero-img" alt="Registration SketchConvert" data-aos="zoom-out" data-aos-delay="300">
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



                <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start mb-4">
                    <h3 class="fw-bold mb-0">Create an account</h3>
                </div>

                <!-- Google Sign Up Button -->
                <div class="mb-4">
                    <a href="{{ route('google.redirect') }}"
                       class="btn btn-outline-dark btn-lg w-100 d-flex align-items-center justify-content-center"
                       style="border-radius: 12px; padding: 0.75rem; transition: all 0.3s ease;">
                        <svg width="20" height="20" viewBox="0 0 20 20" class="me-2">
                            <path d="M19.6 10.23c0-.82-.1-1.42-.25-2.05H10v3.72h5.5c-.15.96-.74 2.31-2.04 3.22v2.45h3.16c1.89-1.73 2.98-4.3 2.98-7.34z" fill="#4285F4"/>
                            <path d="M13.46 15.13c-.83.59-1.96 1-3.46 1-2.64 0-4.88-1.74-5.68-4.15H1.07v2.52C2.72 17.75 6.09 20 10 20c2.7 0 4.96-.89 6.62-2.42l-3.16-2.45z" fill="#34A853"/>
                            <path d="M3.99 10c0-.69.12-1.35.32-1.97V5.51H1.07A9.973 9.973 0 000 10c0 1.61.39 3.14 1.07 4.49l3.24-2.52c-.2-.62-.32-1.28-.32-1.97z" fill="#FBBC05"/>
                            <path d="M10 3.88c1.88 0 3.13.81 3.85 1.48l2.84-2.76C14.96.99 12.7 0 10 0 6.09 0 2.72 2.25 1.07 5.51l3.24 2.52C5.12 5.62 7.36 3.88 10 3.88z" fill="#EA4335"/>
                        </svg>
                        Sign up with Google
                    </a>
                </div>

                <!-- Divider OR -->
                <div class="divider d-flex align-items-center my-4">
                    <p class="text-center fw-bold mx-3 mb-0 text-muted">OR</p>
                </div>

                <form id="register-form" method="POST" action="{{ route('register.submit') }}">
                    @csrf

                    <!-- Username -->
                    <div class="form-outline mb-4">
                        <input type="text"
                               id="username"
                               name="username"
                               class="form-control form-control-lg @error('username') is-invalid @enderror"
                               value="{{ old('username') }}"
                               placeholder=" "
                               required />
                        <label class="form-label" for="username">Username</label>
                        @error('username')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email input -->
                    <div class="form-outline mb-4">
                        <input type="email"
                               id="email"
                               name="email"
                               class="form-control form-control-lg @error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               placeholder=" "
                               required />
                        <label class="form-label" for="email">Email address</label>
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password input with strength indicator -->
                    <div class="form-outline mb-3">
                        <input type="password"
                               id="password"
                               name="password"
                               class="form-control form-control-lg @error('password') is-invalid @enderror"
                               placeholder=" "
                               minlength="8"
                               required />
                        <label class="form-label" for="password">Password</label>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Minimum 8 characters</small>

                        <!-- Password strength indicator -->
                        <div id="password-strength" class="mt-2" style="display: none;">
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar" role="progressbar"></div>
                            </div>
                            <small class="text-muted" id="strength-text"></small>
                        </div>
                    </div>

                    <!-- Password confirmation -->
                    <div class="form-outline mb-4">
                        <input type="password"
                               id="password_confirmation"
                               name="password_confirmation"
                               class="form-control form-control-lg"
                               placeholder=" "
                               required />
                        <label class="form-label" for="password_confirmation">Confirm password</label>
                    </div>

                    <div class="text-center text-lg-start mt-4 pt-2">
                        <button type="submit"
                                class="btn btn-primary btn-lg"
                                style="padding-left: 2.5rem; padding-right: 2.5rem;">
                            Sign up
                        </button>
                        <p class="small fw-bold mt-2 pt-1 mb-0">
                            Already have an account?
                            <a href="{{ route('login') }}" class="link-primary">Log in</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

</section>
@endsection

@push('styles')
<style>
    /* Style pour le divider OR */
    .divider:before,
    .divider:after {
        content: "";
        flex: 1;
        height: 1px;
        background: #e0e0e0;
    }

    /* Hover effect sur le bouton Google */
    .btn-outline-dark:hover {
        background-color: #f8f9fa !important;
        border-color: #dee2e6 !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    /* Animation du SVG Google au survol */
    .btn-outline-dark:hover svg {
        transform: scale(1.1);
        transition: transform 0.3s ease;
    }

    /* Ajustement du logo mobile */
    @media (max-width: 767px) {
        .text-center img {
            max-width: 250px !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {

    // Function to calculate password strength
    function calculatePasswordStrength(password) {
        let strength = 0;

        if (password.length >= 8) strength += 25;
        if (password.length >= 12) strength += 25;
        if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 25;
        if (/\d/.test(password)) strength += 15;
        if (/[^a-zA-Z0-9]/.test(password)) strength += 10;

        return Math.min(strength, 100);
    }

    // Function to display password strength
    function updatePasswordStrength(password) {
        const strengthBar = $('#password-strength');
        const progressBar = strengthBar.find('.progress-bar');
        const strengthText = $('#strength-text');

        if (password.length === 0) {
            strengthBar.hide();
            return;
        }

        strengthBar.show();
        const strength = calculatePasswordStrength(password);

        progressBar.css('width', strength + '%');

        if (strength < 40) {
            progressBar.removeClass().addClass('progress-bar bg-danger');
            strengthText.text('Weak').css('color', '#dc3545');
        } else if (strength < 70) {
            progressBar.removeClass().addClass('progress-bar bg-warning');
            strengthText.text('Medium').css('color', '#ffc107');
        } else {
            progressBar.removeClass().addClass('progress-bar bg-success');
            strengthText.text('Strong').css('color', '#28a745');
        }
    }

    // Real-time password validation
    $('#password').on('input', function() {
        const password = $(this).val();

        // Update strength indicator
        updatePasswordStrength(password);

        // Length validation
        if (password.length > 0 && password.length < 8) {
            $(this).addClass('is-invalid').removeClass('is-valid');
        } else if (password.length >= 8) {
            $(this).removeClass('is-invalid').addClass('is-valid');

            // Check confirmation if it exists
            const confirmation = $('#password_confirmation').val();
            if (confirmation) {
                $('#password_confirmation').trigger('input');
            }
        }
    });

    // Password confirmation validation
    $('#password_confirmation').on('input', function() {
        const password = $('#password').val();
        const confirmation = $(this).val();

        if (confirmation.length > 0) {
            if (password !== confirmation) {
                $(this).addClass('is-invalid').removeClass('is-valid');
            } else {
                $(this).removeClass('is-invalid').addClass('is-valid');
            }
        } else {
            $(this).removeClass('is-invalid is-valid');
        }
    });

    // Username validation (real-time)
    $('#username').on('input', function() {
        const username = $(this).val().trim();

        if (username.length > 0 && username.length < 3) {
            $(this).addClass('is-invalid').removeClass('is-valid');
        } else if (username.length >= 3) {
            $(this).removeClass('is-invalid').addClass('is-valid');
        }
    });

    // Email validation (real-time)
    $('#email').on('blur', function() {
        const email = $(this).val().trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (email && emailRegex.test(email)) {
            $(this).removeClass('is-invalid').addClass('is-valid');
        } else if (email) {
            $(this).addClass('is-invalid').removeClass('is-valid');
        }
    });

    // Validation before submission
    $('#register-form').on('submit', function(e) {
        const password = $('#password').val();
        const confirmation = $('#password_confirmation').val();
        const username = $('#username').val().trim();
        const email = $('#email').val().trim();

        let isValid = true;
        let errorMsg = '';

        if (username.length < 3) {
            isValid = false;
            errorMsg = 'Username must contain at least 3 characters.';
            $('#username').addClass('is-invalid').focus();
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            isValid = false;
            errorMsg = 'Please enter a valid email address.';
            $('#email').addClass('is-invalid').focus();
        } else if (password.length < 8) {
            isValid = false;
            errorMsg = 'Password must contain at least 8 characters.';
            $('#password').addClass('is-invalid').focus();
        } else if (password !== confirmation) {
            isValid = false;
            errorMsg = 'Passwords do not match.';
            $('#password_confirmation').addClass('is-invalid').focus();
        }

        if (!isValid) {
            e.preventDefault();

            // Display elegant error message
            const alertHtml = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ti ti-alert-circle me-2"></i>${errorMsg}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`;

            $('.alert').remove();
            $('#register-form').prepend(alertHtml);

            setTimeout(function() {
                $('.alert').fadeOut('slow', function() { $(this).remove(); });
            }, 5000);

            return false;
        }

        // Disable button to prevent double submissions
        $(this).find('button[type="submit"]')
            .prop('disabled', true)
            .html('<span class="spinner-border spinner-border-sm me-2"></span>Registering...');
    });

    // Enter key support for smooth navigation
    $('#username').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#email').focus();
        }
    });

    $('#email').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#password').focus();
        }
    });

    $('#password').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#password_confirmation').focus();
        }
    });
});
</script>
@endpush
