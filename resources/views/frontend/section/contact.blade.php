@extends('frontend.layout.app')

@section('content')

<section id="contact" class="contact section">

    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up" style="padding-top: 80px;">
        <h2>Contact</h2>
        <p>Contact us for any questions or concerns.</p>
    </div><!-- End Section Title -->

    <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">

            <div class="col-lg-6">
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="200">
                    <i class="bi bi-geo-alt"></i>
                    <h3>Address</h3>
                    <p>Compass Building, Al Shohada Road</p>
                    <p>Ras Al Khaimah, UAE</p>
                </div>
            </div><!-- End Info Item -->

            <div class="col-lg-3 col-md-6">
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="300">
                    <i class="bi bi-whatsapp"></i>
                    <h3>WhatsApp</h3>
                    <p>+33 7 64 38 60 72</p>
                </div>
            </div><!-- End Info Item -->

            <div class="col-lg-3 col-md-6">
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="400">
                    <i class="bi bi-envelope"></i>
                    <h3>Email Us</h3>
                    <p>{{ config('mail.to_address') }}</p>
                </div>
            </div><!-- End Info Item -->

        </div>

        <div class="row gy-4 mt-1">
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="300">
                <iframe src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3608.4365844!2d55.78626!3d25.66509!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMjXCsDM5JzU0LjMiTiA1NcKwNDcnMTAuNSJF!5e0!3m2!1sen!2sae!4v1234567890" frameborder="0" style="border:0; width: 100%; height: 400px; border-radius: 12px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div><!-- End Google Maps -->

            <div class="col-lg-6">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('contact.send') }}" method="POST" class="php-email-form" data-aos="fade-up" data-aos-delay="400">
                    @csrf
                    <div class="row gy-4">

                        <div class="col-md-6">
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   placeholder="Your Name"
                                   value="{{ old('name', auth()->check() ? auth()->user()->username : '') }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   name="email"
                                   placeholder="Your Email"
                                   value="{{ old('email', auth()->check() ? auth()->user()->email : '') }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <input type="text" class="form-control @error('subject') is-invalid @enderror"
                                   name="subject"
                                   placeholder="Subject"
                                   value="{{ old('subject') }}"
                                   required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <textarea class="form-control @error('message') is-invalid @enderror"
                                      name="message"
                                      rows="6"
                                      placeholder="Message"
                                      required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary px-5 py-3">
                                <i class="bi bi-send me-2"></i>Send Message
                            </button>
                        </div>

                    </div>
                </form>
            </div><!-- End Contact Form -->

        </div>

    </div>

</section>

@endsection

@push('styles')
<style>

    .header {
        color: var(--default-color);
        background-color: white;
        padding: 20px 0;
        transition: all 0.5s;
        z-index: 997;
    }

    #contact{
        padding-top: 170px;
    }
    /* Espacement entre header et section contact */
    .contact.section {
        padding-top: 60px;
    }

    /* Style des info-items */
    .info-item {
        padding: 30px;
        background: #f8f9fa;
        border-radius: 12px;
        transition: all 0.3s ease;
        height: 100%;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .info-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .info-item i {
        font-size: 48px;
        color: var(--accent-color, #007bff);
        margin-bottom: 15px;
    }

    .info-item h3 {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 10px;
        color: #2c3e50;
    }

    .info-item p {
        margin: 0;
        color: #6c757d;
        font-size: 15px;
        line-height: 1.6;
    }

    /* Style du formulaire */
    .php-email-form {
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    }

    .php-email-form .form-control {
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        padding: 12px 15px;
        transition: all 0.3s ease;
    }

    .php-email-form .form-control:focus {
        border-color: var(--accent-color, #007bff);
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.15);
    }

    .php-email-form textarea.form-control {
        resize: vertical;
        min-height: 150px;
    }

    .php-email-form button[type="submit"] {
        border-radius: 8px;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s ease;
        border: none;
    }

    .php-email-form button[type="submit"]:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,123,255,0.3);
    }

    /* Style des alertes */
    .alert {
        border-radius: 10px;
        border: none;
        padding: 15px 20px;
        margin-bottom: 25px;
        font-weight: 500;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
    }

    /* Section title */
    .section-title {
        text-align: center;
        padding-bottom: 40px;
    }

    .section-title h2 {
        font-size: 48px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 15px;
    }

    .section-title p {
        font-size: 18px;
        color: #6c757d;
    }

    /* Responsive */
    @media (max-width: 768px) {

        #contact{
            padding-top: 90px;
        }
        .contact.section {
            padding-top: 40px;
        }

        .section-title {
            padding-top: 60px !important;
        }

        .section-title h2 {
            font-size: 36px;
        }

        .info-item {
            margin-bottom: 20px;
        }

        .php-email-form {
            padding: 20px;
        }
    }
</style>
@endpush