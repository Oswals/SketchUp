<!DOCTYPE html>
<html lang="en">

<head>

  @include('frontend.partials.header')

    <style>
        /* Styles pour améliorer l'apparence de la zone de drop */
        #dropzone {
            transition: all 0.3s ease;
        }

        #dropzone:hover {
            border-color: #0d6efd !important;
        }

        .form-select:focus,
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .btn-primary {
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
        }
    </style>

    <style>
        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }

        .modal-header {
            padding: 1.5rem;
        }

        .modal-body {
            padding: 2rem;
        }

        #loginModal .btn-lg {
            padding: 0.75rem 1.5rem;
            font-weight: 600;
        }

        #loginModal .alert {
            border-radius: 10px;
        }
   </style>

   <style>
       /* Style pour l'icône de profil */
       .user-icon-link {
           display: inline-flex;
           align-items: center;
           justify-content: center;
           text-decoration: none;
           transition: all 0.3s ease;
       }

       .user-icon {
           width: 45px;
           height: 45px;
           font-size: 240px;
           background-color: #3150BF;
           border-radius: 50%;
           display: flex;
           align-items: center;
           justify-content: center;
           transition: all 0.3s ease;
           box-shadow: 0 2px 8px rgba(56, 141, 168, 0.2);
       }

       .user-icon i {
           color: #ffffff;
           font-size: 24px;
           line-height: 1; /* ← Ajoutez cette ligne */
           padding-right: 6px;  /* ← Optionnel mais peut aider */
       }

       .user-icon-link:hover .user-icon {
           background-color: #2130cdff;
           transform: scale(1.05);
           box-shadow: 0 4px 12px rgba(56, 141, 168, 0.4);
       }

    /* Responsive */
    @media (max-width: 768px) {
        .user-icon {
        width: 40px;
        height: 40px;
        }

        .user-icon i {
        font-size: 20px;
        }
    }
    </style>

   <!-- ==================== STYLES MODAL PRICING UNIFIÉ ==================== -->
    <style>
        .pricing-card {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 30px;
            height: 100%;
        }

        .pricing-card-popular {
            border-color: #3150BF;
        }

        .btn-primary {
            background-color: #3150BF;
            border-color: #3150BF;
        }

        .btn-primary:hover {
            background-color: #2a44a3;
            border-color: #2a44a3;
        }

        .text-primary {
            color: #3150BF !important;
        }

        /* Différencier les deux boutons */
        .btn-getstarted[href*="login"] {
            background-color: white !important;
            color: #3150BF !important;
            border: 2px solid #3150BF !important;
        }

        .btn-getstarted[href*="login"]:hover {
            background-color: #f8f9fa !important;
            color: #194ec7 !important;
            border-color: #194ec7 !important;
            transform: translateY(-2px);
        }
    </style>

    <style>
        /* Dropdown profile menu - Style Liste Accentué */
        .profile-dropdown {
            position: relative;
        }

        .profile-dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 12px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
            min-width: 240px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-15px);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
            border: 1px solid #e5e7eb;
        }

        .profile-dropdown-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .profile-dropdown-menu ul {
            list-style: none;
            padding: 6px;
            margin: 0;
        }

        .profile-dropdown-menu li {
            margin: 0;
        }

        .profile-dropdown-menu a,
        .profile-dropdown-menu button {
            display: flex;
            align-items: center;
            padding: 14px 16px;
            color: #374151;
            text-decoration: none;
            transition: all 0.2s ease;
            background: white;
            border: none;
            width: 100%;
            text-align: left;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            border-radius: 6px;
            border: 2px solid transparent;
        }

        /* Account Settings - Bleu accentué */
        .profile-dropdown-menu .menu-account:hover {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            border-color: #2563eb;
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .profile-dropdown-menu .menu-account:hover i {
            transform: rotate(90deg);
        }

        /* Log Out - Rouge accentué */
        .profile-dropdown-menu .menu-logout:hover {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border-color: #dc2626;
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .profile-dropdown-menu .menu-logout:hover i {
            transform: translateX(3px);
        }

        .profile-dropdown-menu i {
            margin-right: 14px;
            font-size: 20px;
            width: 24px;
            text-align: center;
            transition: transform 0.2s ease;
        }

        .profile-dropdown-menu .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #e5e7eb, transparent);
            margin: 6px 10px;
        }

        /* User icon clickable */
        .user-icon-link {
            cursor: pointer;
        }

        /* Animation de l'icône active */
        .user-icon-link.active .user-icon {
            background-color: #2130cdff;
            transform: scale(1.05);
        }

        /* Badge compteur (optionnel) */
        .profile-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 10px;
            border: 2px solid white;
        }


    </style>

    <style>
        /*--------------------------------------------------------------
        # Testimonials Section
        --------------------------------------------------------------*/
        .testimonials .testimonial-item {
        background-color: var(--surface-color);
        box-shadow: 0px 0 20px rgba(0, 0, 0, 0.1);
        box-sizing: content-box;
        padding: 30px;
        margin: 40px 30px;
        min-height: 320px;
        display: flex;
        flex-direction: column;
        text-align: center;
        transition: 0.3s;
        }

        .testimonials .testimonial-item .stars {
        margin-bottom: 15px;
        }

        .testimonials .testimonial-item .stars i {
        color: #ffc107;
        margin: 0 1px;
        }

        .testimonials .testimonial-item .testimonial-img {
        width: 90px;
        border-radius: 50%;
        border: 4px solid var(--background-color);
        margin: 0 auto;
        }

        .testimonials .testimonial-item h3 {
        font-size: 18px;
        font-weight: bold;
        margin: 10px 0 5px 0;
        }

        .testimonials .testimonial-item h4 {
        font-size: 14px;
        color: color-mix(in srgb, var(--default-color), transparent 40%);
        margin: 0;
        }

        .testimonials .testimonial-item p {
        font-style: italic;
        margin: 0 auto 15px auto;
        }

        .testimonials .swiper-wrapper {
        height: auto;
        }

        .testimonials .swiper-pagination {
        margin-top: 20px;
        position: relative;
        }

        .testimonials .swiper-pagination .swiper-pagination-bullet {
        width: 12px;
        height: 12px;
        background-color: color-mix(in srgb, var(--default-color), transparent 85%);
        opacity: 1;
        }

        .testimonials .swiper-pagination .swiper-pagination-bullet-active {
        background-color: var(--accent-color);
        }

        .testimonials .swiper-slide {
        opacity: 0.3;
        }

        @media (max-width: 1199px) {
        .testimonials .swiper-slide-active {
            opacity: 1;
        }

        .testimonials .swiper-pagination {
            margin-top: 0;
        }

        .testimonials .testimonial-item {
            margin: 40px 20px;
        }
        }

        @media (min-width: 1200px) {
        .testimonials .swiper-slide-next {
            opacity: 1;
            transform: scale(1.12);
        }
        }
    </style>

    <style>
        /* Trust badge dans le modal pricing */
        #pricingModal .trust-badge-modal {
            display: inline-block;
            background: #f8f9ff;
            border: 1px solid #e0e4f5;
            border-radius: 50px;
            padding: 8px 20px;
            font-size: 0.9rem;
            color: #333;
        }
        #pricingModal .trust-badge-modal .stars {
            color: #ffb400;
            margin-right: 6px;
        }

        /* Ruban promo sur la carte Unlimited */
        #pricingModal .pricing-card.position-relative {
            overflow: visible;
        }
        #pricingModal .promo-ribbon {
            position: absolute;
            top: -12px;
            right: 15px;
            background: #ff6b35;
            color: #fff;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 5px 14px;
            border-radius: 20px;
            box-shadow: 0 3px 8px rgba(255, 107, 53, 0.4);
            z-index: 2;
        }
    </style>



    @stack('styles')
</head>

<body class="index-page">

    <nav id="header" class="header d-flex align-items-center fixed-top">
        <div class="container-fluid container-xl position-relative d-flex align-items-center">

            <!-- Version PC (cachée sur mobile) -->

            <a href="{{ url('/') }}" class="logo d-none d-lg-flex align-items-center me-auto">
                <img src="{{ asset("frontend/assets/img/favicon_sketchup.png") }}" style="padding-right: 10px;" width="80" height="70" alt="">
                <h1 class="sitename" style="color: black; ">DownConvert <br> SketchUp Files</h1>
            </a>

            {{--<a href="{{ url('/') }}" class="logo d-none d-lg-flex align-items-center me-auto">
                <img src="{{ asset('frontend/assets/img/3.png') }}" width="400" height="70">
            </a>--}}

            <!-- Version Mobile (cachée sur PC) -->

            <a href="{{ url('/') }}" class="logo d-flex d-lg-none align-items-center me-auto">
                <img src="{{ asset("frontend/assets/img/favicon_sketchup.png") }}" style="padding-right: 10px;" width="60" height="60" alt="">
                <h1 class="sitename" style="color: black; font-size: 20px;  ">DownConvert <br> SketchUp Files</h1>
            </a>

            {{--<a href="{{ url('/') }}" class="logo d-flex d-lg-none align-items-center me-auto">
                <img src="{{ asset('frontend/assets/img/3.png') }}" width="240" height="55">
            </a>--}}

            <nav id="navmenu" class="navmenu">
                <div class="mobile-nav-overlay"></div>
                <ul>
                    <a href="{{ url('/') }}" class="logo d-flex d-lg-none align-items-center me-auto">
                        <img src="{{ asset('frontend/assets/img/3.png') }}" width="240" height="55">
                    </a>
                    @php
                        $currentRoute = Route::currentRouteName();
                        $isResultOrProfile = in_array($currentRoute, ['conversion.result', 'profile.index', 'profile','contact','coins.checkout','coins.success','coins.history']);
                    @endphp

                    <!-- Home
                    @if($isResultOrProfile)
                        <li><a href="{{ route('home') }}" class="{{ $currentRoute === 'home' ? 'active' : '' }}">Home</a></li>
                    @else
                        <li><a href="#hero-conversion" class="active">Home</a></li>
                    @endif
                    -->

                    @if($isResultOrProfile)
                        <li><a href="{{ route('home') }}" class="{{ $currentRoute === 'home' ? 'active' : '' }}">Home</a></li>
                    @else

                    @endif

                    <!-- FAQ
                    @unless($isResultOrProfile)
                        <li><a href="#content-section">FAQ</a></li>
                    @endunless
                     -->


                   <!-- Pricing -->
                    @if($isResultOrProfile)
                        <li>
                            <a href="#"
                            data-bs-toggle="modal"
                            data-bs-target="#pricingModal">
                                Pricing
                            </a>
                        </li>
                    @else
                    @endif


                    @if($isResultOrProfile)
                        <!-- Contact -->
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                    @else

                    @endif


                    <!-- Mes Conversions -->
                    @auth
                        <li><a href="{{ route('conversion.result') }}" class="{{ $currentRoute === 'conversion.result' ? 'active' : '' }}">My Conversions</a></li>
                    @endauth

                    <!-- Get Started -->
                    @unless($isResultOrProfile)

                    @endunless

                    <!-- 🆕 COIN BALANCE (uniquement si authentifié)
                    @auth
                        <a  class="coin-balance-minimal" id="coinBalanceNav" href="{{route('coins.history')}}" title="Your Coin Balance">
                            <span class="coin-icon">🪙</span>
                            <span class="coin-amount" id="navCoinBalance">{{ auth()->user()->coin_balance ?? 0 }}</span>
                        </a>
                    @endauth -->


                    <!-- Profil ou Connexion -->
                    @auth
                        <!-- Version Desktop - avec icône -->
                        <div class="profile-dropdown d-none d-lg-block">
                            <a class="user-icon-link" id="profileToggle" title="My Account">
                                <div class="user-icon">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                            </a>

                            <div class="profile-dropdown-menu" id="profileDropdown">
                                <div>
                                    <a href="{{ route('profile.index') }}" class="menu-account">
                                        <span>Account Settings</span>
                                    </a>
                                </div>

                                <div>
                                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                                        @csrf
                                        <button type="submit" class="menu-logout">
                                            <i class="bi bi-box-arrow-right"></i>
                                            <span>Logout</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Version Mobile - options directes -->
                        <li class="d-lg-none mobile-profile-item">
                            <a href="{{ route('profile.index') }}">
                                <i class="bi bi-gear-fill"></i>
                                <span>Account Settings</span>
                            </a>
                        </li>

                        <li class="d-lg-none mobile-profile-item">
                            <form method="POST" action="{{ route('logout') }}" style="margin: 0; width: 100%;">
                                @csrf
                                <button type="submit" style="width: 100%; text-align: left; background: none; border: none; padding: 16px 10px; color: var(--nav-dropdown-color); font-family: var(--nav-font); font-size: 16px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 10px;">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </li>
                    @else

                            <a class="btn-getstarted" href="{{ route('login') }}">Sign In </a>

                            <a class="btn-getstarted" href="{{ route('register') }}">Sign Up </a>

                    @endauth
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>

        </div>
    </nav>


    <!-- ==================== PRICING MODAL ==================== -->
    <!-- ==================== PRICING MODAL ==================== -->
    <div class="modal fade" id="pricingModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <!-- HEADER -->
                <div class="modal-header" style="background: #3150BF; color: white;">
                    <div class="w-100 text-center">
                        <h3 class="modal-title fw-bold mb-1" style="color: white;">Choose Your Plan</h3>
                        <p class="mb-0 small" style="color: white;">One-time payment • No subscription</p>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <!-- BODY -->
                <div class="modal-body p-4">

                    <!-- Trust badge -->
                    <div class="text-center mb-4">
                        <div class="trust-badge-modal">
                            <span class="stars">★★★★★</span>
                            <strong>+7,500 users</strong> already trust DownConvert
                        </div>
                    </div>

                    <div class="row g-4 justify-content-center">

                        <!-- 1 FILE -->
                        <div class="col-lg-5">
                            <div class="pricing-card">
                                <h4 class="text-center mb-2">1 File</h4>
                                <p class="text-center text-muted small mb-3">For one-time needs</p>

                                <div class="text-center mb-4">
                                    <span class="h2 fw-bold text-primary">$0.90</span>
                                </div>

                                @auth
                                    <a href="{{ route('coins.checkout', 1) }}" class="btn btn-primary w-100 mb-4">
                                        Buy Now
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-primary w-100 mb-4">
                                        Login to Buy
                                    </a>
                                @endauth

                                <ul class="list-unstyled">
                                    <li class="mb-2">✓ 1 SketchUp conversion</li>
                                    <li class="mb-2">✓ Never expires</li>
                                    <li class="mb-2">✓ Files up to 100 MB</li>
                                    <li class="mb-2">✓ All versions supported</li>
                                    <li class="mb-2">✓ Email support</li>
                                </ul>
                            </div>
                        </div>

                        <!-- UNLIMITED -->
                        <div class="col-lg-5">
                            <div class="pricing-card pricing-card-popular position-relative">
                                <span class="promo-ribbon">Save 70%</span>
                                <div class="badge bg-warning text-dark mb-2">⭐ Best Value</div>
                                <h4 class="text-center mb-2">Unlimited</h4>
                                <p class="text-center text-muted small mb-3">For professionals</p>

                                <div class="text-center mb-2">
                                    <span class="h2 fw-bold text-primary">$19.90</span>
                                </div>
                                <p class="text-center text-muted small mb-3">One-time • Never expires</p>

                                @auth
                                    <a href="{{ route('coins.checkout', 2) }}" class="btn btn-warning w-100 mb-4">
                                        Buy Now
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-warning w-100 mb-4">
                                        Login to Buy
                                    </a>
                                @endauth

                                <ul class="list-unstyled">
                                    <li class="mb-2">✓ Unlimited SketchUp conversions</li>
                                    <li class="mb-2">✓ Never expires</li>
                                    <li class="mb-2">✓ Files up to 100 MB</li>
                                    <li class="mb-2">✓ All versions supported</li>
                                    <li class="mb-2">✓ Priority support</li>
                                    <li class="mb-2">✓ Complete history</li>
                                </ul>
                            </div>
                        </div>

                    </div>

                    <!-- Message de réassurance -->
                    <div class="text-center text-muted mt-4 small">
                        <i class="bi bi-shield-check text-success me-1"></i>
                        Secure payment by Stripe &nbsp;•&nbsp;
                        <i class="bi bi-lock-fill text-success me-1"></i>
                        No subscription, no hidden fees
                    </div>

                </div>
            </div>
        </div>
    </div>


    <main id="main">
        @yield('content')


    </main>


  @include('frontend.partials.footer')

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="{{ asset('frontend/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('frontend/assets/vendor/php-email-form/validate.js') }}"></script>
  <script src="{{ asset('frontend/assets/vendor/aos/aos.js') }}"></script>
  <script src="{{ asset('frontend/assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ asset('frontend/assets/vendor/swiper/swiper-bundle.min.js') }}"></script>

  <!-- Main JS File -->
  <script src="{{ asset('frontend/assets/js/main.js') }}"></script>

  @stack('scripts')



    <script>
        // Fermer le menu en cliquant sur l'overlay
        document.addEventListener('DOMContentLoaded', function() {
            const navmenu = document.querySelector('.navmenu');

            navmenu.addEventListener('click', function(e) {
                // Si on clique sur l'overlay (le ::before), fermer le menu
                if (e.target === navmenu && document.body.classList.contains('mobile-nav-active')) {
                    document.body.classList.remove('mobile-nav-active');
                }
            });
        });


        document.addEventListener('DOMContentLoaded', function() {
            const navmenu = document.querySelector('.navmenu');
            const menuUl = document.querySelector('.navmenu > ul');
            const body = document.body;

            let touchStartX = 0;
            let touchEndX = 0;

            // Fermer le menu en cliquant sur l'overlay
            navmenu.addEventListener('click', function(e) {
                if (e.target === navmenu && body.classList.contains('mobile-nav-active')) {
                    closeMenu();
                }
            });

            // Gestion du swipe (glisser pour fermer)
            if (menuUl) {
                menuUl.addEventListener('touchstart', function(e) {
                    touchStartX = e.changedTouches[0].screenX;
                }, false);

                menuUl.addEventListener('touchend', function(e) {
                    touchEndX = e.changedTouches[0].screenX;
                    handleSwipe();
                }, false);
            }

            function handleSwipe() {
                // Si swipe de gauche vers droite (au moins 50px)
                if (touchEndX > touchStartX + 50) {
                    closeMenu();
                }
            }

            function closeMenu() {
                body.classList.remove('mobile-nav-active');
            }
        });

        // Force l'icône à rester en "trois traits"
        document.querySelector('.mobile-nav-toggle').addEventListener('click', function() {
            // Empêche le changement d'icône
            this.classList.remove('bi-x');
            this.classList.add('bi-list');
        });
    </script>


  <!-- Script JavaScript pour le drag & drop et interactions -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropzone = document.getElementById('dropzone');
            const fileInput = document.getElementById('sketchupFile');
            const browseBtn = document.getElementById('browseBtn');
            const dropzoneContent = document.getElementById('dropzoneContent');
            const fileInfo = document.getElementById('fileInfo');
            const removeFileBtn = document.getElementById('removeFile');
            const conversionForm = document.getElementById('conversionForm');
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const loadingText = document.getElementById('loadingText');

            // Check if user is authenticated
            const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
            console.log('🔐 User authenticated:', isAuthenticated);

            if (browseBtn) {
                browseBtn.addEventListener('click', () => fileInput.click());
            }

            if (dropzone) {
                dropzone.addEventListener('click', (e) => {
                    if (e.target === dropzone || e.target === dropzoneContent) {
                        fileInput.click();
                    }
                });

                // Drag & drop handling
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    dropzone.addEventListener(eventName, preventDefaults, false);
                });

                ['dragenter', 'dragover'].forEach(eventName => {
                    dropzone.addEventListener(eventName, () => {
                        dropzone.style.borderColor = '#0d6efd';
                        dropzone.style.backgroundColor = '#e7f1ff';
                    });
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    dropzone.addEventListener(eventName, () => {
                        dropzone.style.borderColor = '#dee2e6';
                        dropzone.style.backgroundColor = '#f8f9fa';
                    });
                });

                dropzone.addEventListener('drop', (e) => {
                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        fileInput.files = files;
                        handleFileSelect(files[0]);
                    }
                });
            }

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            if (fileInput) {
                fileInput.addEventListener('change', (e) => {
                    if (e.target.files.length > 0) {
                        handleFileSelect(e.target.files[0]);
                    }
                });
            }

            function handleFileSelect(file) {
                const extension = file.name.split('.').pop().toLowerCase();
                if (extension !== 'skp') {
                    alert('⚠️ Please select a SketchUp file (.skp)');
                    fileInput.value = '';
                    return;
                }

                const maxSize = 100 * 1024 * 1024;
                if (file.size > maxSize) {
                    alert('⚠️ File is too large. Maximum size: 100 MB');
                    fileInput.value = '';
                    return;
                }

                document.getElementById('fileName').textContent = file.name;
                document.getElementById('fileSize').textContent = formatFileSize(file.size);

                dropzoneContent.classList.add('d-none');
                fileInfo.classList.remove('d-none');
            }

            if (removeFileBtn) {
                removeFileBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    fileInput.value = '';
                    dropzoneContent.classList.remove('d-none');
                    fileInfo.classList.add('d-none');
                });
            }

            // Form submission handling
            if (conversionForm) {
                conversionForm.addEventListener('submit', function(e) {
                    console.log('📤 Form submitted');

                    if (!fileInput.files.length) {
                        e.preventDefault();
                        alert('⚠️ Please select a file');
                        return;
                    }

                    const targetVersion = document.getElementById('targetVersion').value;
                    if (!targetVersion) {
                        e.preventDefault();
                        alert('⚠️ Please select a target version');
                        return;
                    }

                    if (!isAuthenticated) {
                        console.log('🔒 User not authenticated - Starting AJAX request');
                        e.preventDefault();

                        submitBtn.disabled = true;
                        submitText.classList.add('d-none');
                        loadingText.textContent = 'Saving file...';
                        loadingText.classList.remove('d-none');

                        const formData = new FormData(conversionForm);

                        fetch('{{ route("conversion.convert") }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                            .then(response => {
                                const contentType = response.headers.get('content-type');
                                if (!contentType || !contentType.includes('application/json')) {
                                    throw new Error('Server response is not in JSON format');
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.requires_auth) {
                                    const modalElement = document.getElementById('loginModal');
                                    if (!modalElement) {
                                        alert('⚠️ Please log in to convert your files.');
                                        window.location.href = '{{ route("login") }}';
                                        return;
                                    }

                                    const loginModal = new bootstrap.Modal(modalElement);
                                    loginModal.show();

                                    submitBtn.disabled = false;
                                    submitText.classList.remove('d-none');
                                    loadingText.classList.add('d-none');

                                } else if (data.insufficient_coins) {
                                    // ✅ Insufficient coins - Scroll to pricing (HOME only)
                                    console.log('💰 Insufficient coins - Scrolling to pricing section');

                                    const modalElement = document.getElementById('insufficientCoinsModal');
                                    if (modalElement) {
                                        // Update balance
                                        const balanceSpan = document.getElementById('currentBalance');
                                        if (balanceSpan) {
                                            balanceSpan.textContent = data.current_balance || 0;
                                        }

                                        const modal = new bootstrap.Modal(modalElement);
                                        modal.show();
                                    } else {
                                        // Fallback: direct scroll if modal not found
                                        scrollToPricing();
                                    }

                                    submitBtn.disabled = false;
                                    submitText.classList.remove('d-none');
                                    loadingText.classList.add('d-none');

                                } else if (data.success === false) {
                                    alert('❌ ' + (data.message || 'An error occurred'));
                                    submitBtn.disabled = false;
                                    submitText.classList.remove('d-none');
                                    loadingText.classList.add('d-none');
                                }
                            })
                            .catch(error => {
                                console.error('❌ Error:', error);

                                // ✅ Ne pas afficher d'alerte si c'est juste une erreur de format JSON ou Failed to fetch
                                if (error.message.includes('JSON format') ||
                                    error.message.includes('Failed to fetch') ||
                                    error.message.includes('NetworkError')) {

                                    console.log('🔒 Likely requires authentication - Opening login modal silently');

                                    // Ouvrir le modal de login sans message d'erreur
                                    const modalElement = document.getElementById('loginModal');
                                    if (modalElement) {
                                        const loginModal = new bootstrap.Modal(modalElement);
                                        loginModal.show();
                                    } else {
                                        window.location.href = '{{ route("login") }}';
                                    }
                                } else {
                                    // Pour les vraies erreurs, afficher le message
                                    alert('❌ An error occurred.\n\n' + error.message);
                                }

                                submitBtn.disabled = false;
                                submitText.classList.remove('d-none');
                                loadingText.classList.add('d-none');
                            });

                        return false;
                    }

                    // ✅ If authenticated, normal submission
                    submitBtn.disabled = true;
                    submitText.classList.add('d-none');
                    loadingText.textContent = 'Conversion in progress...';
                    loadingText.classList.remove('d-none');

                    return true;
                });
            }

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
            }
        });
    </script>

    <!-- Script de gestion du modal login -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const goToLoginBtn = document.getElementById('goToLoginBtn');
            const loginModalElement = document.getElementById('loginModal');
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const loadingText = document.getElementById('loadingText');

            // ✅ Redirection vers login
            if (goToLoginBtn) {
                goToLoginBtn.addEventListener('click', function() {
                    console.log('🔗 Redirecting to login page');
                    window.location.href = "{{ route('login') }}";
                });
            }

            // ✅ Événement : Modal fermé
            if (loginModalElement) {
                loginModalElement.addEventListener('hidden.bs.modal', function () {
                    console.log('🔄 Modal closed - Cleaning up');

                    // 1. Réactiver le formulaire
                    resetFormState();

                    // 2. Nettoyer les classes Bootstrap sur body
                    cleanupBootstrapModal();

                    console.log('✅ Page unlocked and form ready');
                });

                // ✅ Gérer aussi le bouton close (×)
                const closeButtons = loginModalElement.querySelectorAll('[data-bs-dismiss="modal"]');
                closeButtons.forEach(btn => {
                    btn.addEventListener('click', function() {
                        console.log('❌ Close button clicked');
                        // Bootstrap gérera la fermeture, on nettoie juste après
                    });
                });
            }

            // ✅ Fonction pour réinitialiser l'état du formulaire
            function resetFormState() {
                if (submitBtn) {
                    submitBtn.disabled = false;
                }

                if (submitText) {
                    submitText.classList.remove('d-none');
                }

                if (loadingText) {
                    loadingText.classList.add('d-none');
                    loadingText.textContent = 'Saving file...';
                }
            }

            // ✅ Fonction pour nettoyer les classes Bootstrap
            function cleanupBootstrapModal() {
                // Supprimer les classes de modal sur body
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';

                // Supprimer tous les backdrops (arrière-plans gris)
                const backdrops = document.querySelectorAll('.modal-backdrop');
                backdrops.forEach(backdrop => {
                    backdrop.remove();
                });

                console.log('🧹 Bootstrap modal classes cleaned');
            }

            // ✅ Sécurité : Nettoyer au clic n'importe où sur le backdrop
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('modal-backdrop')) {
                    console.log('🖱️ Backdrop clicked - Forcing cleanup');
                    cleanupBootstrapModal();
                    resetFormState();
                }
            });
        });
    </script>

    <!-- ==================== FONCTION GLOBALE PRICING ==================== -->
    <script>
        // Global function to open the pricing modal
        function openPricingModal(event) {
            if (event) event.preventDefault();

            console.log('🏷️ Opening pricing modal');

            const pricingModalElement = document.getElementById('pricingModal');
            if (!pricingModalElement) {
                console.error('❌ Pricing modal not found');
                return;
            }

            // Adapt the title depending on the context
            const titleElement = document.getElementById('pricingModalTitle');
            const subtitleElement = document.getElementById('pricingModalSubtitle');
            const balanceAlert = document.getElementById('coinBalanceAlert');

            @auth
            // If the user is authenticated and has no coins
            const userBalance = parseInt('{{ auth()->user()->coin_balance ?? 0 }}');
            if (userBalance < 1) {
                if (titleElement) titleElement.textContent = 'Insufficient Balance';
                if (subtitleElement) subtitleElement.textContent = 'You have no more coins. Buy some to continue.';
                if (balanceAlert) {
                    balanceAlert.style.background = 'linear-gradient(135deg, #ffe7e7 0%, #fff0f0 100%)';
                    balanceAlert.querySelector('i').style.color = '#dc3545';
                    balanceAlert.querySelector('strong').style.color = '#dc3545';
                }
            } else {
                if (titleElement) titleElement.textContent = 'Our Offers & Pricing';
                if (subtitleElement) subtitleElement.textContent = 'Choose the package that suits you';
                if (balanceAlert) {
                    balanceAlert.style.background = 'linear-gradient(135deg, #e7f1ff 0%, #f0e7ff 100%)';
                    balanceAlert.querySelector('i').style.color = '#667eea';
                    balanceAlert.querySelector('strong').style.color = '#667eea';
                }
            }
            @endauth

            // Open the modal
            const pricingModal = new bootstrap.Modal(pricingModalElement, {
                backdrop: 'static',
                keyboard: true
            });
            pricingModal.show();
        }

        // Handler for the "Pricing" link in the navbar
        document.addEventListener('DOMContentLoaded', function() {
            const pricingLink = document.getElementById('openPricingModal');

            if (pricingLink) {
                pricingLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    openPricingModal();
                });
            }
        });
    </script>

    <!-- ==================== FONCTION Profile==================== -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const profileToggle = document.getElementById('profileToggle');
            const profileDropdown = document.getElementById('profileDropdown');

            if (profileToggle && profileDropdown) {
                // Toggle dropdown au clic
                profileToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    // Toggle classe active sur l'icône
                    profileToggle.classList.toggle('active');
                    profileDropdown.classList.toggle('show');
                });

                // Fermer le dropdown quand on clique ailleurs
                document.addEventListener('click', function(e) {
                    if (!profileToggle.contains(e.target) && !profileDropdown.contains(e.target)) {
                        profileDropdown.classList.remove('show');
                        profileToggle.classList.remove('active');
                    }
                });

                // Fermer le dropdown avec la touche Escape
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        profileDropdown.classList.remove('show');
                        profileToggle.classList.remove('active');
                    }
                });

                // Animation au survol des items
                const menuItems = profileDropdown.querySelectorAll('a, button');
                menuItems.forEach(item => {
                    item.addEventListener('mouseenter', function() {
                        this.style.transition = 'all 0.2s ease';
                    });
                });
            }
        });
    </script>

    <script>
     // Fonction pour mettre à jour le coin balance en temps réel
        function updateNavCoinBalance(newBalance) {
            const balanceElement = document.getElementById('navCoinBalance');
            const modalBalanceElement = document.getElementById('modalCoinBalance');

            if (balanceElement) {
                // Animation de mise à jour
                balanceElement.style.transition = 'transform 0.3s ease';
                balanceElement.style.transform = 'scale(1.3)';

                setTimeout(() => {
                    balanceElement.textContent = newBalance;
                    balanceElement.style.transform = 'scale(1)';
                }, 150);
            }

            // Mettre à jour aussi le modal si présent
            if (modalBalanceElement) {
                modalBalanceElement.textContent = newBalance;
            }
        }

        // Exemple d'utilisation après un achat de coins
        // updateNavCoinBalance(10);
    </script>

    <script>
        // ✅ Configuration globale - fonctionne pour TOUS les utilisateurs
        @if(auth()->check())
            const isAuthenticated = true;
            const userBalance = {{ auth()->user()->coin_balance ?? 0 }};
        @else
            const isAuthenticated = false;
            const userBalance = 0;
        @endif

        const isHomePage = {{ request()->is('/') || request()->is('home') ? 'true' : 'false' }};

        // ✅ Intercepter la soumission du formulaire de conversion
        document.addEventListener('DOMContentLoaded', function() {
            const conversionForm = document.getElementById('conversionForm');

            if (conversionForm) {
                conversionForm.addEventListener('submit', function(e) {
                    // ❌ BLOQUER si pas connecté
                    if (!isAuthenticated) {
                        e.preventDefault();
                        console.log('🔒 User not authenticated - Opening login modal');
                        openLoginModal();
                        return false;
                    }

                    // ❌ BLOQUER si pas de balance
                    if (userBalance <= 0) {
                        e.preventDefault();
                        console.log('💰 Insufficient balance');

                        if (isHomePage) {
                            scrollToPricingSection();
                        } else {
                            openPricingModal();
                        }
                        return false;
                    }
                });
            }
        });

        // 🔐 Fonction pour ouvrir le modal de login (utilisateur non connecté)
        function openLoginModal() {
            const loginModal = document.getElementById('loginModal');
            if (loginModal) {
                const modal = new bootstrap.Modal(loginModal, {
                    backdrop: 'static',
                    keyboard: true
                });
                modal.show();
            } else {
                // Si pas de modal, rediriger vers la page de login
                window.location.href = '{{ route("login") }}';
            }
        }

        // ✅ Fonction pour scroller vers la section pricing (HOME)
        function scrollToPricingSection() {
            console.log('🏠 Home page - Scrolling to #pricing');

            const pricingSection = document.getElementById('pricing');
            if (pricingSection) {
                const headerOffset = 80;
                const elementPosition = pricingSection.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });

                // Effet de highlight
                pricingSection.classList.add('highlighted');
                setTimeout(() => {
                    pricingSection.classList.remove('highlighted');
                }, 2000);

                // Notification
                showBalanceNotification();
            }
        }

        // ✅ Fonction pour ouvrir le modal pricing (PROFILE)
        function openPricingModal() {
            console.log('👤 Profile page - Opening #pricingModal');

            const pricingModal = document.getElementById('pricingModal');
            if (pricingModal) {
                const modal = new bootstrap.Modal(pricingModal, {
                    backdrop: 'static',
                    keyboard: true
                });
                modal.show();

                // Personnaliser le message
                const modalTitle = pricingModal.querySelector('.modal-title');
                if (modalTitle) {
                    modalTitle.innerHTML = '<i class="bi bi-exclamation-circle me-2"></i>Insufficient Balance';
                }
            }
        }

        // ✅ Notification pour la page home
        function showBalanceNotification() {
            const notification = document.createElement('div');
            notification.className = 'alert alert-warning alert-dismissible fade show position-fixed';
            notification.style.cssText = 'top: 100px; right: 20px; z-index: 9999; max-width: 400px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
            notification.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="bi bi-wallet2 me-2 fs-5"></i>
                    <div>
                        <strong>Insufficient Balance!</strong>
                        <p class="mb-0 small">You need at least 1 coin to convert files. Check our plans below.</p>
                    </div>
                    <button type="button" class="btn-close ms-2" data-bs-dismiss="alert"></button>
                </div>
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                if (notification && notification.parentNode) {
                    notification.classList.remove('show');
                    setTimeout(() => notification.remove(), 150);
                }
            }, 8000);
        }
    </script>



    @if(session('insufficient_balance_home'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => scrollToPricingSection(), 500);
        });
    </script>
    @endif

    @if(session('insufficient_balance_profile'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => openPricingModal(), 500);
        });
    </script>
    @endif



</body>
</html>
</body>

</html>
