@extends('frontend.layout.app')

@push('title', 'Home - Sketch2Convert')

@push('styles')
<style>

    .header {
        color: var(--default-color);
        background-color: white;
        padding: 20px 0;
        transition: all 0.5s;
        z-index: 997;
    }
    /* Hero Conversion Section - Style héro avec gradient */
    #hero-conversion {
        background: linear-gradient(135deg, #3951bcff 0%, #194ec7ff 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        padding-top: 150px;
        padding-bottom: 50px;
        position: relative;
        overflow: hidden;
    }

    #hero-conversion::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,165.3C1248,171,1344,149,1392,138.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
        background-size: cover;
        opacity: 0.5;
    }

    #hero-conversion .container {
        position: relative;
        z-index: 2;
    }

    /* Titre héro */
    #hero-conversion .hero-title {
        color: #ffffff;
        font-size: 3.5rem;
        font-weight: 800;
        text-align: center;
        margin-bottom: 1.5rem;
        text-shadow: 0 4px 12px rgba(0,0,0,0.2);
        animation: fadeInDown 1s ease;
    }

    #hero-conversion .hero-subtitle {
        color: rgba(255,255,255,0.95);
        font-size: 1.3rem;
        text-align: center;
        margin-bottom: 3rem;
        font-weight: 300;
        animation: fadeInUp 1s ease 0.2s both;
    }

    /* Card de conversion avec effet glassmorphism */
    #hero-conversion .conversion-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 25px;
        box-shadow: 0 20px 60px #3150BF;
        border: 1px solid rgba(255,255,255,0.3);
        animation: fadeInUp 1s ease 0.4s both;
        max-width: 700px;
        margin: 0 auto;
    }

    #hero-conversion .card-body {
        padding: 3rem;
    }

    /* Boutons primary avec couleur personnalisée */
    #hero-conversion .btn-primary,
    #loginModal .btn-primary {
        background: linear-gradient(135deg, #3150BF 0%, #3150BF 100%);
        border: none;
        box-shadow: 0 4px 15px #3150BF;
        transition: all 0.3s ease;
    }

    #hero-conversion .btn-primary:hover,
    #loginModal .btn-primary:hover {
        background: linear-gradient(135deg, #3150BF 0%, #3150BF 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px #3150BF;
    }

    #hero-conversion .btn-outline-primary,
    #loginModal .btn-outline-primary {
        color: #3150BF;
        border-color: #3150BF;
        transition: all 0.3s ease;
    }

    #hero-conversion .btn-outline-primary:hover,
    #loginModal .btn-outline-primary:hover {
        background-color: #3150BF;
        border-color: #3150BF;
        color: #fff;
        transform: translateY(-2px);
    }

    #hero-conversion .text-primary,
    #loginModal .text-primary {
        color: #3150BF !important;
    }

    /* Dropzone améliorée */
    #hero-conversion #dropzone {
        border: 3px dashed #dee2e6;
        border-radius: 15px;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }

    #hero-conversion #dropzone:hover {
        border-color: #3150BF;
        background: #f0f8ff;
        transform: scale(1.02);
    }

    /* Animations */
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Form controls */
    #hero-conversion .form-control,
    #hero-conversion .form-select {
        border-radius: 12px;
        border: 2px solid #e9ecef;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    #hero-conversion .form-control:focus,
    #hero-conversion .form-select:focus {
        border-color: #3150BF;
        box-shadow: 0 0 0 0.25rem #3150BF;
    }

    /* Responsive */
    @media (max-width: 768px) {
        #hero-conversion {
            min-height: auto;
            padding-top: 200px;
            padding-bottom: 60px;
        }

        #hero-conversion .hero-title {
            font-size: 2.5rem;
        }

        #hero-conversion .hero-subtitle {
            font-size: 1.1rem;
        }

        #hero-conversion .card-body {
            padding: 2rem 1.5rem;
        }
    }

    /* Features section légère */
    #features-light {
        padding: 80px 0;
        background: #ffffff;
    }

    #features-light .feature-box {
        text-align: center;
        padding: 2rem;
        border-radius: 15px;
        transition: all 0.3s ease;
    }

    #features-light .feature-box:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 30px #3150BF;
    }

    #features-light .feature-icon {
        font-size: 3rem;
        color:#3150BF ;
        margin-bottom: 1rem;
    }

    #features-light .feature-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 1rem;
    }

    #features-light .feature-description {
        color: #666;
        line-height: 1.6;
    }

    /* ✅ Highlight pour la section pricing */
    #pricing.highlighted {
        animation: pulseHighlight 2s ease-in-out;
    }

    @keyframes pulseHighlight {
        0%, 100% {
            background-color: transparent;
        }
        50% {
            background-color: #3150BF;
        }
    }
</style>


    <style>
        /* ── Pain cards ── */
        .why-pain-card {
            background: #fff5f5;
            border: 1px solid #fecaca;
            border-radius: 12px;
            padding: 1.2rem 1rem;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }
        .why-pain-card p {
            margin: 0;
            font-size: 0.88rem;
            font-weight: 600;
            color: #7f1d1d;
            text-align: center;
        }
        .why-pain-icon {
            font-size: 1.6rem;
            color: #dc3545;
        }

        /* ── Solution phrase sobre ── */
        /* (inline, pas de classe nécessaire) */

        /* ── Steps layout ── */
        .step-col {
            flex: 1 1 220px;
            max-width: 280px;
            padding: 0 8px;
        }
        .step-arrow-col {
            flex: 0 0 40px;
            padding-top: 1rem;
        }

        /* ── Step cards ── */
        .step-card {
            background: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 16px;
            padding: 2rem 1.5rem;
            text-align: center;
            height: 100%;
            position: relative;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .step-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 32px rgba(49, 80, 191, 0.12);
        }
        .step-num {
            position: absolute;
            top: -14px;
            left: 50%;
            transform: translateX(-50%);
            width: 28px;
            height: 28px;
            background: #3150BF;
            color: white;
            font-size: 0.75rem;
            font-weight: 700;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .step-icon {
            font-size: 2.2rem;
            color: #3150BF;
            margin-bottom: 1rem;
            margin-top: 0.5rem;
        }
        .step-title {
            font-size: 1rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        .step-desc {
            font-size: 0.88rem;
            color: #6c757d;
            margin: 0;
            line-height: 1.5;
        }

        /* ── Flèche entre les steps ── */
        .step-arrow {
            font-size: 1.4rem;
            color: #adb5bd;
            margin-top: 1rem;
        }

        /* ── Timing badge ── */
        .step-timing {
            background: #f0f4ff;
            border: 1px solid #c7d5f8;
            color: #3150BF;
            font-size: 0.88rem;
            padding: 10px 22px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
        }
        .step-timing strong { color: #1e3a9e; }
    </style>

<style>
.stat-box {
    padding: 2rem 1rem;
    border-radius: 15px;
    transition: all 0.3s ease;
}
.stat-box:hover {
    transform: translateY(-8px);
    box-shadow: 0 10px 30px rgba(49, 80, 191, 0.12);
}
.stat-stars {
    font-size: 1.1rem;
    margin-bottom: 0.75rem;
}
.stat-icon {
    font-size: 2rem;
    color: #3150BF;
    margin-bottom: 0.75rem;
}
.stat-number {
    font-size: 2.5rem;
    font-weight: 800;
    color: #3150BF;
    line-height: 1;
    margin-bottom: 0.5rem;
}
.stat-label {
    font-size: 0.95rem;
    color: #6c757d;
    font-weight: 500;
}
</style>



    <style>
        /** ── Eyebrow ── */
        .hf-eyebrow {
            font-size: 0.78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.8px;
            color: #3150BF;
            margin-bottom: 10px;
        }

        /* ── Titre ── */
        .hf-title {
            font-size: 2.6rem;
            font-weight: 800;
            color: #2c3e50;
            line-height: 1.2;
            margin-bottom: 0.9rem;
        }

        /* ── Sous-titre ── */
        .hf-subtitle {
            font-size: 1.1rem;
            font-weight: 400;
            color: #555;
            margin-bottom: 0.9rem;
            line-height: 1.6;
        }
        .hf-subtitle strong { color: #3150BF; }

        /* ── Versions ── */
        .hf-versions {
            font-size: 0.88rem;
            color: #6c757d;
            margin-bottom: 1.4rem;
            line-height: 1.7;
        }

        /* ── Pills ── */
        .hf-pill {
            display: inline-flex;
            align-items: center;
            background: #f0f4ff;
            border: 1px solid #c7d5f8;
            color: #3150BF;
            font-size: 0.78rem;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 20px;
        }
        .hf-pill i { color: #22c55e; }

        /* ── CTA ── */
        .hf-cta-btn {
            background: linear-gradient(135deg, #3150BF 0%, #1e3a9e 100%);
            border: none;
            color: white;
            font-weight: 700;
            font-size: 1rem;
            padding: 13px 36px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(49, 80, 191, 0.3);
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            text-decoration: none;
        }
        .hf-cta-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 22px rgba(49, 80, 191, 0.4);
            color: white;
        }

        /* ── Trust strip ── */
        .hf-trust {
            display: flex;
            flex-direction: column;
            gap: 5px;
            font-size: 0.88rem;
            color: #6c757d;
        }
        .hf-trust strong { color: #2c3e50; }
        .hf-stars {
            color: #f59e0b;
            letter-spacing: 2px;
            margin-right: 4px;
        }

        /* ── Media wrap ── */
        .hf-media-wrap {
            position: relative;
            display: inline-block;
        }

        .hf-video {
            width: 100%;
            max-width: 580px;
            border-radius: 16px;
            box-shadow: 0 16px 48px rgba(49, 80, 191, 0.18), 0 4px 12px rgba(0,0,0,0.1);
            display: block;
            margin: 0 auto;
        }

        /* ── Badge flottant ── */
        .hf-media-badge {
            position: absolute;
            bottom: -14px;
            left: 50%;
            transform: translateX(-50%);
            background: #3150BF;
            color: #fff;
            font-size: 0.76rem;
            font-weight: 700;
            padding: 7px 18px;
            border-radius: 20px;
            white-space: nowrap;
            box-shadow: 0 4px 14px rgba(49, 80, 191, 0.35);
        }
        .hf-media-badge i { color: #fbbf24; }

        /* ── Responsive ── */
        @media (max-width: 992px) {
            .hf-title { font-size: 2rem; }
            .hf-video { max-width: 340px; }
        }
        @media (max-width: 576px) {
            .hf-title    { font-size: 1.7rem; }
            .hf-subtitle { font-size: 1rem; }
        }
    </style>


    <style>
        .trusted-strip {
            border-top: 1px solid #e9ecef;
            padding-top: 2rem;
            text-align: center;
        }

        .trusted-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.8px;
            color: #adb5bd;
            margin-bottom: 1.2rem;
        }

        .trusted-stats {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            flex-wrap: wrap;
        }

        .trusted-stat {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0 2.5rem;
        }

        .trusted-number {
            font-size: 2rem;
            font-weight: 800;
            color: #3150BF;
            line-height: 1;
            margin-bottom: 4px;
        }

        .trusted-desc {
            font-size: 0.82rem;
            color: #6c757d;
            font-weight: 500;
        }

        .trusted-divider {
            width: 1px;
            height: 40px;
            background: #dee2e6;
        }

        @media (max-width: 576px) {
            .trusted-stat { padding: 0.8rem 1.5rem; }
            .trusted-divider { display: none; }
        }
    </style>


@endpush

@section('content')

<!-- Hero Conversion Section -->
<section id="hero-conversion">
    <div class="container">

        <!-- Card de conversion -->
        <div class="conversion-card card border-0">
            <div class="card-body">
                <form action="{{ route('conversion.convert') }}" method="POST" enctype="multipart/form-data" id="conversionForm">
                    @csrf

                    <!-- Titre avec icône -->
                    <div class="text-center mb-4">
                        <h3 class="d-flex align-items-center justify-content-center">
                            <i class="bi bi-file-earmark-arrow-up fs-2 me-2 text-primary"></i>
                            <span>Downconvert <span style="background-color: #3150bf; color: white;"> &nbsp;SketchUp </span> &nbsp;files from newer versions to older ones</span>
                        </h3>
                    </div>

                    <!-- Zone de téléchargement -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label fw-bold">SketchUp File</label>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-secondary" title="Import from link">
                                    <i class="bi bi-link-45deg"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Zone de drag & drop -->
                        <div class="border border-2 border-dashed rounded p-5 text-center bg-light position-relative"
                             id="dropzone"
                             style="min-height: 200px; cursor: pointer; transition: all 0.3s;">
                            <input type="file"
                                   name="sketchup_file"
                                   id="sketchupFile"
                                   class="d-none"
                                   accept=".skp"
                                   required>

                            <div id="dropzoneContent">
                                <i class="bi bi-cloud-arrow-up fs-1 text-primary mb-3 d-block"></i>
                                <h5 class="mb-2">Drag & Drop</h5>
                                <p class="text-muted small mb-3">
                                    SketchUp Files (.skp)
                                </p>
                                <p class="text-muted small mb-3">— OR —</p>
                                <button type="button" class="btn btn-primary" id="browseBtn">
                                    Browse Files
                                </button>
                            </div>

                            <div id="fileInfo" class="d-none">
                                <i class="bi bi-file-earmark-check fs-1 text-success mb-2 d-block"></i>
                                <p class="fw-bold mb-1" id="fileName"></p>
                                <p class="text-muted small" id="fileSize"></p>
                                <button type="button" class="btn btn-sm btn-outline-danger mt-2" id="removeFile">
                                    <i class="bi bi-trash"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Version SketchUp cible -->
                    <div class="mb-4">
                        <label for="targetVersion" class="form-label fw-bold">Target SketchUp Version</label>
                        <select name="target_version" id="targetVersion" class="form-select" required>
                            <option value="" disabled selected>Select a version</option>
                            <option value="2017">SketchUp 2017</option>
                            <option value="2018">SketchUp 2018</option>
                            <option value="2019">SketchUp 2019</option>
                            <option value="2020">SketchUp 2020</option>
                            <option value="2021">SketchUp 2021</option>
                            <option value="2022">SketchUp 2022</option>
                            <option value="2023">SketchUp 2023</option>
                            <option value="2024">SketchUp 2024</option>
                            <option value="2025">SketchUp 2025</option>
                            <option value="2026">SketchUp 2026</option>
                        </select>
                        <small class="text-muted">Choose the version you want to convert your file to</small>
                    </div>

                    <!-- Messages d'erreur -->
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        <strong>Error!</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Bouton de soumission -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                            <span id="submitText">
                                <i class="bi bi-arrow-repeat me-2"></i>
                                Convert
                            </span>
                            <span id="loadingText" class="d-none">
                                <span class="spinner-border spinner-border-sm me-2"></span>
                                Redirecting...
                            </span>
                        </button>
                    </div>

                    <!-- Note légale -->
                    <p class="text-center text-muted small mt-3 mb-0">
                        By using this service, you accept our
                        <a href="" target="_blank">terms of use</a> and our
                        <a href="" target="_blank">privacy policy</a>.
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>




<!-- Modal de connexion -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="loginModalLabel">
                    <i class="bi bi-lock-fill me-2 text-primary"></i>
                    Login Required
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-4">
                    <i class="bi bi-shield-check text-primary" style="font-size: 4rem;"></i>
                </div>
                <h4 class="mb-3">Secure your conversions</h4>
                <p class="text-muted mb-4">
                    To convert your SketchUp files and access your history,
                    log in or create a free account.
                </p>

                <div class="d-grid gap-2 mb-3">
                    <button type="button" class="btn btn-primary btn-lg" id="goToLoginBtn">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Log In
                    </button>
                    <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-person-plus me-2"></i>
                        Create Account
                    </a>
                </div>

                <div class="alert alert-info text-start mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    <small>
                        <strong>Benefits:</strong><br>
                        • Conversion history<br>
                        • Files available for 7 days<br>
                        • Quality preserved
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>





{{-- ============================================================
     HERO + MORE-FEATURES FUSIONNÉS
     Style natif QuickStart : fond blanc, section more-features
     ============================================================ --}}
    <section id="" class="more-features section">

        <div class="container">
            <div class="row justify-content-around align-items-center gy-4">

                {{-- ── COLONNE GAUCHE : texte + CTA ── --}}
                <div class="col-lg-6 d-flex flex-column justify-content-center order-2 order-lg-1" data-aos="fade-up" data-aos-delay="100">

                    <p class="hf-eyebrow">Compatibility issue?</p>

                    <h1 class="hf-title">
                        Can't Open a<br>SketchUp File?
                    </h1>

                    <h2 class="hf-subtitle">
                        DownConvert Newer SketchUp Files to Older Versions <strong>Instantly</strong>
                    </h2>

                    <p class="hf-versions">
                        Open SketchUp 2026 files in SketchUp 2025, 2024, 2023, 2022, 2021, 2020, 2019, 2018 and 2017.
                    </p>

                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <span class="hf-pill"><i class="bi bi-check-circle-fill me-1"></i> No installation</span>
                        <span class="hf-pill"><i class="bi bi-check-circle-fill me-1"></i> No plugins</span>
                        <span class="hf-pill"><i class="bi bi-check-circle-fill me-1"></i> No upgrade required</span>
                    </div>

                    <div class="mb-4">
                        <a href="#conversionForm" class="btn hf-cta-btn btn-lg">
                            <i class="bi bi-arrow-repeat me-2"></i>
                            Convert My File
                        </a>
                    </div>

                    <div class="hf-trust">
                        <div>
                            <span class="hf-stars">★★★★★</span>
                            Trusted by <strong>7,500+</strong> users
                        </div>
                        <div>
                            <i class="bi bi-file-earmark-check me-1"></i>
                            <strong>6,580+</strong> files converted successfully
                        </div>
                    </div>

                </div>

                {{-- ── COLONNE DROITE : vidéo en autoplay (remplace GIF) ── --}}
                <div class="col-lg-5 order-1 order-lg-2 text-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="hf-media-wrap">
                        <video
                            class="hf-video"
                            autoplay
                            muted
                            loop
                            playsinline
                            disablePictureInPicture
                            controlsList="nodownload nofullscreen noremoteplayback"
                            poster="{{ asset('frontend/assets/videos/promo-poster.jpg') }}"
                        >
                            <source src="{{ asset('frontend/assets/videos/promo.mp4') }}" type="video/mp4">
                        </video>
                        <div class="hf-media-badge">
                            <i class="bi bi-lightning-charge-fill me-1"></i> Conversion in seconds
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="why-happen" class="featured-services section light-background">
        <div class="container">

            {{-- ── BLOC 1 : WHY DOES THIS HAPPEN ── --}}
            <div class="row justify-content-center mb-5" data-aos="fade-up">
                <div class="col-lg-8 text-center">
                    <h2 style="font-weight:800; color:#2c3e50; margin-bottom:0.75rem;">Why Does This Happen?</h2>
                    <p style="font-size:1.05rem; color:#555; margin-bottom:0.4rem;">
                        SketchUp files are <strong>not backward compatible.</strong>
                    </p>
                    <p style="color:#6c757d; margin-bottom:2rem;">
                        A file created in a newer version of SketchUp cannot be opened in older versions.
                    </p>

                    {{-- 3 pain points --}}
                    <div class="row g-3 justify-content-center mb-4">
                        <div class="col-md-4">
                            <div class="why-pain-card">
                                <i class="bi bi-person-x why-pain-icon"></i>
                                <p>Client files become unusable</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="why-pain-card">
                                <i class="bi bi-people why-pain-icon"></i>
                                <p>Team collaboration becomes difficult</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="why-pain-card">
                                <i class="bi bi-cash-stack why-pain-icon"></i>
                                <p>Upgrading SketchUp becomes expensive</p>
                            </div>
                        </div>
                    </div>

                    {{-- Solution phrase sobre --}}
                    <p style="color:#6c757d; font-size:0.92rem; margin-top:0.5rem;">
                        <strong style="color:#3150BF;">DownConvert</strong> solves this problem instantly.
                    </p>
                </div>
            </div>

            {{-- Séparateur --}}
            <hr style="border-color:#dee2e6; margin-bottom:3rem;" data-aos="fade-up">

            {{-- ── BLOC 2 : 3 SIMPLE STEPS ── --}}
            <div class="row justify-content-center mb-4" data-aos="fade-up">
                <div class="col-lg-8 text-center">
                    <h2 style="font-weight:800; color:#2c3e50; margin-bottom:0.4rem;">Convert Your File In 3 Simple Steps</h2>
                </div>
            </div>

            <div class="d-flex align-items-stretch justify-content-center gap-0 flex-wrap flex-md-nowrap" data-aos="fade-up" data-aos-delay="100">

                <div class="step-col">
                    <div class="step-card">
                        <div class="step-num">1</div>
                        <div class="step-icon"><i class="bi bi-cloud-arrow-up"></i></div>
                        <h4 class="step-title">Upload</h4>
                        <p class="step-desc">Upload your SketchUp file securely.</p>
                    </div>
                </div>
                <div class="step-arrow-col d-none d-md-flex align-items-center justify-content-center">
                    <i class="bi bi-arrow-right step-arrow"></i>
                </div>
                <div class="step-col">
                    <div class="step-card">
                        <div class="step-num">2</div>
                        <div class="step-icon"><i class="bi bi-sliders"></i></div>
                        <h4 class="step-title">Choose Target Version</h4>
                        <p class="step-desc">Select the SketchUp version you need.</p>
                    </div>
                </div>
                <div class="step-arrow-col d-none d-md-flex align-items-center justify-content-center">
                    <i class="bi bi-arrow-right step-arrow"></i>
                </div>
                <div class="step-col">
                    <div class="step-card">
                        <div class="step-num">3</div>
                        <div class="step-icon"><i class="bi bi-download"></i></div>
                        <h4 class="step-title">Download</h4>
                        <p class="step-desc">Receive your compatible SketchUp file.</p>
                    </div>
                </div>

            </div>

            {{-- Timing badge --}}
            <div class="row justify-content-center mt-4" data-aos="fade-up" data-aos-delay="200">
                <div class="col-auto">
                    <div class="step-timing">
                        <i class="bi bi-clock me-2"></i>
                        Average conversion time: <strong>less than 60 seconds</strong>
                    </div>
                </div>
            </div>

        </div>
    </section>

{{-- ============================================================
     SECTION 2 : WHY USE DOWNCONVERT ?
     Style : more-features du template (icon-boxes en grille)
     ============================================================ --}}
    <section id="why-downconvert" class="more-features section">
        <div class="container">

            <div class="section-title text-center mb-5" data-aos="fade-up">
                <h2>Why Use DownConvert?</h2>
                <p>The simplest way to fix SketchUp compatibility issues — instantly.</p>
            </div>

            <div class="row justify-content-center gy-4">

                <div class="col-lg-10">
                    <div class="row" data-aos="fade-up" data-aos-delay="100">

                        <div class="col-lg-6 icon-box d-flex mb-4">
                            <i class="bi bi-piggy-bank flex-shrink-0" style="color:#3150BF; font-size:2rem; margin-right:1rem;"></i>
                            <div>
                                <h4>Save Money</h4>
                                <p>Avoid expensive SketchUp upgrades. No subscription, no license fees — just pay per conversion.</p>
                            </div>
                        </div>

                        <div class="col-lg-6 icon-box d-flex mb-4">
                            <i class="bi bi-lightning-charge flex-shrink-0" style="color:#3150BF; font-size:2rem; margin-right:1rem;"></i>
                            <div>
                                <h4>Save Time</h4>
                                <p>No need to contact clients or colleagues to resend files. Convert in seconds, get back to work immediately.</p>
                            </div>
                        </div>

                        <div class="col-lg-6 icon-box d-flex mb-4">
                            <i class="bi bi-check2-circle flex-shrink-0" style="color:#3150BF; font-size:2rem; margin-right:1rem;"></i>
                            <div>
                                <h4>Stay Productive</h4>
                                <p>Open files immediately in your current version of SketchUp. No plugins, no installation required.</p>
                            </div>
                        </div>

                        <div class="col-lg-6 icon-box d-flex mb-4">
                            <i class="bi bi-shield-lock flex-shrink-0" style="color:#3150BF; font-size:2rem; margin-right:1rem;"></i>
                            <div>
                                <h4>Secure by Design</h4>
                                <p>Files are automatically deleted after processing. No human access, no storage, no risk.</p>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            {{-- ── Trusted Worldwide ── --}}
            <div class="row justify-content-center mt-3" data-aos="fade-up" data-aos-delay="200">
                <div class="col-lg-10">
                    <div class="trusted-strip">
                        <p class="trusted-label">Trusted Worldwide</p>
                        <div class="trusted-stats">
                            <div class="trusted-stat">
                                <span class="trusted-number">7,500+</span>
                                <span class="trusted-desc">users</span>
                            </div>
                            <div class="trusted-divider"></div>
                            <div class="trusted-stat">
                                <span class="trusted-number">3,000+</span>
                                <span class="trusted-desc">conversions/mo</span>
                            </div>
                            <div class="trusted-divider"></div>
                            <div class="trusted-stat">
                                <span class="trusted-number">∞</span>
                                <span class="trusted-desc">hours saved</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>


<!-- Testimonials Section -->
@include('frontend.section.testimonials')


<!-- Features légères -->
<section id="features-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-box">
                    <div class="feature-icon">
                        <i class="bi bi-lightning-charge-fill"></i>
                    </div>
                    <h3 class="feature-title">Ultra Fast</h3>
                    <p class="feature-description">
                        Instant conversion of your SketchUp files in seconds.
                    </p>
                </div>
            </div>

            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-box">
                    <div class="feature-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h3 class="feature-title">100% Secure</h3>
                    <p class="feature-description">
                        Your files are protected and automatically deleted after conversion.
                    </p>
                </div>
            </div>

            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-box">
                    <div class="feature-icon">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <h3 class="feature-title">Quality Preserved</h3>
                    <p class="feature-description">
                        All your textures, components and layers are kept intact.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
@include('frontend.section.faq')


<!-- Pricing Section -->
@include('frontend.section.pricing')

<!-- ✅ MODAL INSUFFISANT - Scroll vers Pricing -->
<div class="modal fade" id="insufficientCoinsModal" tabindex="-1" aria-labelledby="insufficientCoinsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px;">
            <div class="modal-header border-0" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white; border-radius: 20px 20px 0 0;">
                <h5 class="modal-title fw-bold" id="insufficientCoinsModalLabel">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Insufficient Balance
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-4">
                    <i class="bi bi-wallet2" style="font-size: 4rem; color: #dc3545;"></i>
                </div>
                <h4 class="mb-3">You have no more files</h4>
                <p class="text-muted mb-4">
                    You need at least <strong>1 file</strong> to perform a conversion.
                </p>

                <div class="alert alert-warning mb-4">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="bi bi-file-earmark me-2" style="font-size: 1.5rem;"></i>
                        <strong>Current balance: 📄 <span id="currentBalance">0</span> file(s)</strong>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-primary btn-lg" onclick="scrollToPricing()">
                        <i class="bi bi-arrow-down-circle me-2"></i>
                        View Plans
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


@include('frontend.section.cta')
@endsection

@push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));

                if (target) {
                    const headerOffset = 80;
                    const elementPosition = target.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    });

    // ✅ FONCTION GLOBALE : Scroll vers la section pricing
    function scrollToPricing() {
        console.log('📍 Scrolling to pricing section');

        // Fermer le modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('insufficientCoinsModal'));
        if (modal) {
            modal.hide();
        }

        // Attendre que le modal soit fermé
        setTimeout(() => {
            const pricingSection = document.getElementById('pricing');
            if (pricingSection) {
                const headerOffset = 80;
                const elementPosition = pricingSection.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });

                // Ajouter un effet de highlight
                pricingSection.classList.add('highlighted');
                setTimeout(() => {
                    pricingSection.classList.remove('highlighted');
                }, 2000);
            }
        }, 300);
    }
    </script>

    @if(auth()->check())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const soldeActuel = {{ auth()->user()->coin_balance ?? 0 }};
            const isUnlimited = soldeActuel >= 9999;

            console.log('💰 Solde actuel:', soldeActuel, '| Unlimited:', isUnlimited);

            // ✅ RÈGLE : Unlimited → jamais de modal | Balance = 0 → modal
            if (!isUnlimited && soldeActuel === 0) {
                console.log('🎯 Balance à 0 - Affichage du modal pricing');
                showPricingModal();
            }

            function showPricingModal() {
                setTimeout(function() {
                    const modalElement = document.getElementById('pricingModal');

                    if (!modalElement) {
                        console.error('❌ Modal #pricingModal introuvable');
                        return;
                    }

                    const pricingModal = new bootstrap.Modal(modalElement, {
                        backdrop: 'static',
                        keyboard: true
                    });

                    // Personnaliser le titre
                    const modalTitle = document.querySelector('#pricingModal .modal-title');
                    if (modalTitle) {
                        modalTitle.innerHTML = '<i class="bi bi-exclamation-circle-fill text-warning me-2"></i>You\'ve used all your files!';
                    }

                    // Personnaliser le sous-titre
                    const modalSubtitle = document.querySelector('#pricingModal .modal-header p');
                    if (modalSubtitle) {
                        modalSubtitle.textContent = 'Buy more files to continue converting';
                    }

                    pricingModal.show();

                }, 1500);
            }
        });
    </script>
@endif
@endpush
