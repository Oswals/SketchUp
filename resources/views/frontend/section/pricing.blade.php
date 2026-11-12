<section id="pricing" class="pricing section">

    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
        <h2>Simple & Transparent Pricing</h2>
        <p>Pay only for what you need - no subscriptions</p>

        <!-- Trust badge -->
        <div class="trust-badge" data-aos="fade-up" data-aos-delay="50">
            <div class="avatars">
                <img src="{{ asset('frontend/assets/img/testimonials/testimonials-1.jpg') }}" alt="user">
                <img src="{{ asset('frontend/assets/img/testimonials/testimonials-2.jpg') }}" alt="user">
                <img src="{{ asset('frontend/assets/img/testimonials/testimonials-3.jpg') }}" alt="user">
            </div>
            <div class="trust-text">
                <span class="stars">★★★★★</span>
                <strong>+7,500 users</strong> trust DownConvert for their SketchUp files
            </div>
        </div>
    </div>

    <div class="container">

        <div class="row gy-4 justify-content-center">

            <!-- Package 1 FILE -->
            <div class="col-lg-4" data-aos="zoom-in" data-aos-delay="100">
                <div class="pricing-item">
                    <h3>1 FILE</h3>
                    <p class="description">For a quick one-time conversion</p>
                    <h4><sup>$</sup>0.90<span> / file</span></h4>
                    <a href="{{ route('coins.checkout', 1) }}" class="cta-btn">
                        Buy Now
                    </a>
                    <p class="text-center small">Secure payment by Stripe</p>
                    <ul>
                        <li><i class="bi bi-check text-success"></i> <span>1 SketchUp conversion</span></li>
                        <li><i class="bi bi-check text-success"></i> <span>No expiration</span></li>
                        <li><i class="bi bi-check text-success"></i> <span>Files up to 100 MB</span></li>
                        <li><i class="bi bi-check text-success"></i> <span>All SketchUp versions</span></li>
                        <li><i class="bi bi-check text-success"></i> <span>Email support</span></li>
                        <li class="na"><i class="bi bi-x text-danger"></i> <span>Bulk discount</span></li>
                        <li class="na"><i class="bi bi-x text-danger"></i> <span>Priority support</span></li>
                    </ul>
                </div>
            </div>

            <!-- Package UNLIMITED (Popular) -->
            <div class="col-lg-4" data-aos="zoom-in" data-aos-delay="200">
                <div class="pricing-item featured">
                    <p class="popular">⭐ Best Value</p>
                    <span class="promo-tag">Save 70% vs paying per file</span>
                    <h3>UNLIMITED</h3>
                    <p class="description">Convert as many files as you want!</p>
                    <h4><sup>$</sup>19.90<span> / one-time</span></h4>
                    <a href="{{ route('coins.checkout', 2) }}" class="cta-btn">Buy Now</a>
                    <p class="text-center small">One payment • Never expires</p>
                    <ul>
                        <li><i class="bi bi-check text-success"></i> <span><strong>Unlimited conversions</strong></span></li>
                        <li><i class="bi bi-check text-success"></i> <span>Never expires</span></li>
                        <li><i class="bi bi-check text-success"></i> <span>Files up to 100 MB</span></li>
                        <li><i class="bi bi-check text-success"></i> <span>All SketchUp versions</span></li>
                        <li><i class="bi bi-check text-success"></i> <span>Priority support</span></li>
                        <li><i class="bi bi-check text-success"></i> <span>Complete history</span></li>
                        <li><i class="bi bi-check text-success"></i> <span>No restrictions</span></li>
                    </ul>
                </div>
            </div>

        </div>

    </div>

</section>

@push('styles')
    <style>
        .pricing-item {
            transition: all 0.3s ease;
        }
        .pricing-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15) !important;
        }
        .pricing-item.featured {
            border: 3px solid #3150BF;
            transform: scale(1.05);
        }
        .pricing-item.featured:hover {
            transform: scale(1.08) translateY(-10px);
        }
        .pricing-item .popular {
            font-size: 1rem;
            font-weight: 600;
        }
        @media (max-width: 768px) {
            .pricing-item.featured {
                transform: scale(1);
            }
        }

        /* Trust badge */
        .trust-badge {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            margin-top: 15px;
            padding: 8px 18px;
            background: #f8f9ff;
            border: 1px solid #e0e4f5;
            border-radius: 50px;
        }
        .trust-badge .avatars {
            display: flex;
        }
        .trust-badge .avatars img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fff;
            margin-left: -10px;
        }
        .trust-badge .avatars img:first-child {
            margin-left: 0;
        }
        .trust-badge .trust-text {
            font-size: 0.9rem;
            color: #333;
        }
        .trust-badge .stars {
            color: #ffb400;
            margin-right: 4px;
        }

        /* Promo tag */
        .pricing-item .promo-tag {
            display: inline-block;
            background: #ff6b35;
            color: #fff;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
            margin-bottom: 10px;
        }
    </style>
@endpush
