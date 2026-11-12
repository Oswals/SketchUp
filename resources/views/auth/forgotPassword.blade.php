@extends('auth.auth')

@section('title', 'Forgot Password')

@section('contents')
<section class="vh-100">
    <div class="container-fluid h-custom">
        <div class="row d-flex justify-content-center align-items-center h-100">

            <!-- Image Section -->
           {{-- <div class="col-md-9 col-lg-6 col-xl-5">
               <img src="{{ asset('frontend/assets/img/two.svg') }}" class="img-fluid hero-img" alt="Forgot Password" data-aos="zoom-out" data-aos-delay="300">
            </div>--}}

            <!-- Form Section -->
            <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">

                <!-- Back to login button -->
                <div class="mb-3">
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="ti ti-arrow-left me-1"></i> Back to login
                    </a>
                </div>

                <form id="forgot-password-form" method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start mb-4">
                        <h3 class="fw-bold mb-0">Forgot Password?</h3>
                    </div>

                    <p class="text-muted mb-4">
                        <i class="ti ti-info-circle me-2"></i>
                        Enter your email address and we will send you a link to reset your password.
                    </p>

                    <!-- Success messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="ti ti-circle-check me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- General error messages -->
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="ti ti-alert-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Email -->
                    <div class="form-outline mb-4">
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               class="form-control form-control-lg @error('email') is-invalid @enderror"
                               placeholder=" "
                               required
                               autofocus />

                        <label class="form-label" for="email">Email address</label>

                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit button -->
                    <div class="text-center text-lg-start mt-4 pt-2">
                        <button type="submit"
                                class="btn btn-primary btn-lg w-100"
                                id="submitBtn">
                            <i class="ti ti-mail me-2"></i>
                            Send reset link
                        </button>

                        <p class="small fw-bold mt-3 pt-1 mb-0 text-center">
                            Remember your password?
                            <a href="{{ route('login') }}" class="link-primary">Log in</a>
                        </p>

                        <p class="small text-center mt-2">
                            Don't have an account yet?
                            <a href="{{ route('register') }}" class="link-primary">Create an account</a>
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
    const form = $('#forgot-password-form');
    const submitBtn = $('#submitBtn');
    const originalBtnText = submitBtn.html();

    // Form validation
    form.on('submit', function(e) {
        const email = $('#email').val().trim();

        // Check if email is valid
        if (!email || !isValidEmail(email)) {
            e.preventDefault();
            showAlert('danger', 'Please enter a valid email address.');
            return false;
        }

        // Disable button and show spinner
        submitBtn.prop('disabled', true)
            .html('<span class="spinner-border spinner-border-sm me-2"></span>Sending...');
    });

    // Function to validate email
    function isValidEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }

    // Function to display alerts
    function showAlert(type, message) {
        $('.alert').remove();

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
            $('.alert').fadeOut('slow', function() {
                $(this).remove();
            });
        }, 5000);
    }

    // Real-time validation
    $('#email').on('input', function() {
        const email = $(this).val().trim();
        if (email && !isValidEmail(email)) {
            $(this).addClass('is-invalid').removeClass('is-valid');
        } else if (email) {
            $(this).removeClass('is-invalid').addClass('is-valid');
        } else {
            $(this).removeClass('is-invalid is-valid');
        }
    });

    // Auto-focus on email
    $('#email').focus();
});
</script>
@endpush
