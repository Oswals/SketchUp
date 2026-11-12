@extends('auth.auth')

@section('title', 'Reset Password')

@section('contents')
<section class="vh-100">
    <div class="container-fluid h-custom">
        <div class="row d-flex justify-content-center align-items-center h-100">

            <!-- Image Section -->
            <div class="col-md-9 col-lg-6 col-xl-5">
               <img src="{{ asset('frontend/assets/img/two.svg') }}" class="img-fluid hero-img" alt="Reset Password" data-aos="zoom-out" data-aos-delay="300">
            </div>

            <!-- Form Section -->
            <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                
                <!-- Back button -->
                <div class="mb-3">
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="ti ti-arrow-left me-1"></i> Back to login
                    </a>
                </div>

                <form id="reset-password-form" method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">

                    <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start mb-4">
                        <h3 class="fw-bold mb-0">New Password</h3>
                    </div>

                    <p class="text-muted mb-4">
                        <i class="ti ti-info-circle me-2"></i>
                        Choose a new secure password for your account.
                    </p>

                    <!-- General error messages -->
                    @if($errors->any() && !$errors->has('password') && !$errors->has('email'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="ti ti-alert-circle me-2"></i>
                            {{ $errors->first() }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Email (readonly) -->
                    <div class="form-outline mb-4">
                        <input type="email" 
                               id="email-display" 
                               value="{{ $email }}"
                               class="form-control form-control-lg"
                               placeholder=" "
                               readonly />
                        <label class="form-label" for="email-display">Email address</label>
                    </div>

                    <!-- New password -->
                    <div class="form-outline mb-3 position-relative">
                        <input type="password" 
                               id="password" 
                               name="password"
                               class="form-control form-control-lg @error('password') is-invalid @enderror"
                               placeholder=" "
                               required
                               minlength="8" />
                        <label class="form-label" for="password">New password</label>
                        
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

                    <!-- Password strength indicator -->
                    <div class="mb-3">
                        <div class="progress" style="height: 5px;">
                            <div id="password-strength" class="progress-bar" role="progressbar" style="width: 0%"></div>
                        </div>
                        <small id="password-strength-text" class="text-muted"></small>
                    </div>

                    <!-- Password confirmation -->
                    <div class="form-outline mb-4 position-relative">
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation"
                               class="form-control form-control-lg"
                               placeholder=" "
                               required />
                        <label class="form-label" for="password_confirmation">Confirm password</label>
                        
                        <button class="btn btn-outline-secondary position-absolute top-50 end-0 translate-middle-y me-2" 
                                type="button" 
                                id="togglePasswordConfirm"
                                style="z-index: 10;">
                            <i class="ti ti-eye-off"></i>
                        </button>
                    </div>

                    <!-- Password requirements -->
                    <div class="alert alert-info mb-4">
                        <small>
                            <strong>Password requirements:</strong><br>
                            <i class="ti ti-check me-1"></i> Minimum 8 characters<br>
                            <i class="ti ti-check me-1"></i> At least one uppercase letter (recommended)<br>
                            <i class="ti ti-check me-1"></i> At least one number (recommended)
                        </small>
                    </div>

                    <!-- Submit button -->
                    <div class="text-center text-lg-start mt-4 pt-2">
                        <button type="submit" 
                                class="btn btn-primary btn-lg w-100"
                                id="submitBtn">
                            <i class="ti ti-lock-check me-2"></i>
                            Reset password
                        </button>

                        <p class="small fw-bold mt-3 pt-1 mb-0 text-center">
                            <a href="{{ route('login') }}" class="link-primary">Back to login</a>
                        </p>
                    </div>

                </form>
            </div>
        </div>
    </div>

</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const form = $('#reset-password-form');
    const password = $('#password');
    const passwordConfirmation = $('#password_confirmation');
    const submitBtn = $('#submitBtn');
    const strengthBar = $('#password-strength');
    const strengthText = $('#password-strength-text');

    // Toggle password visibility
    $('#togglePassword').on('click', function() {
        const type = password.attr('type') === 'password' ? 'text' : 'password';
        password.attr('type', type);
        $(this).find('i').toggleClass('ti-eye-off ti-eye');
    });

    $('#togglePasswordConfirm').on('click', function() {
        const type = passwordConfirmation.attr('type') === 'password' ? 'text' : 'password';
        passwordConfirmation.attr('type', type);
        $(this).find('i').toggleClass('ti-eye-off ti-eye');
    });

    // Password strength validation
    password.on('input', function() {
        const val = $(this).val();
        const strength = calculatePasswordStrength(val);
        
        // Update progress bar
        strengthBar.css('width', strength.percentage + '%')
            .removeClass('bg-danger bg-warning bg-success')
            .addClass(strength.class);
        
        strengthText.text(strength.text).css('color', strength.color);

        // Visual validation
        if (val.length > 0 && val.length < 8) {
            $(this).addClass('is-invalid').removeClass('is-valid');
        } else if (val.length >= 8) {
            $(this).removeClass('is-invalid').addClass('is-valid');
            if (passwordConfirmation.val()) {
                validatePasswordMatch();
            }
        }
    });

    // Password match validation
    passwordConfirmation.on('input', validatePasswordMatch);

    function validatePasswordMatch() {
        if (password.val() !== passwordConfirmation.val()) {
            passwordConfirmation.addClass('is-invalid').removeClass('is-valid');
        } else if (passwordConfirmation.val().length > 0) {
            passwordConfirmation.removeClass('is-invalid').addClass('is-valid');
        }
    }

    // Calculate password strength
    function calculatePasswordStrength(password) {
        let strength = 0;
        
        if (password.length >= 8) strength += 25;
        if (password.length >= 12) strength += 25;
        if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 25;
        if (/\d/.test(password)) strength += 15;
        if (/[^a-zA-Z0-9]/.test(password)) strength += 10;

        if (strength <= 25) {
            return { percentage: 25, class: 'bg-danger', text: 'Weak', color: '#dc3545' };
        } else if (strength <= 50) {
            return { percentage: 50, class: 'bg-warning', text: 'Medium', color: '#ffc107' };
        } else if (strength <= 75) {
            return { percentage: 75, class: 'bg-info', text: 'Good', color: '#0dcaf0' };
        } else {
            return { percentage: 100, class: 'bg-success', text: 'Excellent', color: '#198754' };
        }
    }

    // Form validation
    form.on('submit', function(e) {
        const pwd = password.val();
        const pwdConfirm = passwordConfirmation.val();

        if (pwd.length < 8) {
            e.preventDefault();
            showAlert('danger', 'Password must contain at least 8 characters.');
            password.focus();
            return false;
        }

        if (pwd !== pwdConfirm) {
            e.preventDefault();
            showAlert('danger', 'Passwords do not match.');
            passwordConfirmation.focus();
            return false;
        }

        // Disable button and show spinner
        submitBtn.prop('disabled', true)
            .html('<span class="spinner-border spinner-border-sm me-2"></span>Resetting...');
    });

    // Function to display alerts
    function showAlert(type, message) {
        $('.alert:not(.alert-info)').remove();
        
        const icons = {
            'info': 'ti-info-circle',
            'warning': 'ti-alert-triangle',
            'danger': 'ti-alert-circle',
            'success': 'ti-circle-check'
        };
        
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <i class="ti ${icons[type]} me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>`;
        
        form.prepend(alertHtml);
        
        setTimeout(function() {
            $('.alert:not(.alert-info)').fadeOut('slow', function() {
                $(this).remove();
            });
        }, 5000);
    }

    // Auto-focus on password
    password.focus();
});
</script>
@endpush