@extends('frontend.layout.app')

@section('title', 'Payment - ' . $package->name)

@push('styles')
<style>
    :root {
        --primary-blue: #1565c0;
        --primary-blue-dark: #0d47a1;
        --primary-blue-light: #1976d2;
    }

    .header {
        color: var(--default-color);
        background-color: white;
        padding: 20px 0;
        transition: all 0.5s;
        z-index: 997;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        background: #f5f7fa;
    }

    .checkout-container {
        padding: 80px 0 60px;
    }

    .back-link {
        color: var(--primary-blue);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 30px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .back-link:hover {
        color: var(--primary-blue-dark);
        gap: 12px;
    }

    .page-title {
        font-size: 2rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .payment-card {
        background: white;
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border: 1px solid #e9ecef;
    }

    .summary-card {
        background: white;
        border-radius: 16px;
        padding: 32px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border: 1px solid #e9ecef;
        position: sticky;
        top: 100px;
    }

    .form-control {
        padding: 14px 18px;
        height: auto;
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--primary-blue);
        background-color: white;
        box-shadow: 0 0 0 3px rgba(21, 101, 192, 0.1);
    }

    .form-control:read-only {
        background-color: #e9ecef;
        cursor: not-allowed;
    }

    .section-title {
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        margin-bottom: 16px;
        margin-top: 32px;
    }

    .section-title:first-child {
        margin-top: 0;
    }

    /* ─── Trust Bar ─── */
    .trust-bar {
        background: linear-gradient(135deg, #f0f6ff 0%, #e8f4fd 100%);
        border: 1px solid #d0e7ff;
        border-radius: 12px;
        padding: 14px 20px;
        margin-bottom: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 24px;
        flex-wrap: wrap;
    }

    .trust-bar .trust-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
        color: #2c3e50;
        font-weight: 500;
    }

    .trust-bar .trust-item .stars {
        color: #f59e0b;
        letter-spacing: 1px;
        font-size: 0.85rem;
    }

    .trust-bar .trust-divider {
        width: 1px;
        height: 20px;
        background: #c0d8f0;
    }

    /* ─── Payment Method Cards ─── */
    .payment-method {
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 20px;
        cursor: pointer;
        transition: all 0.25s ease;
        margin-bottom: 12px;
        user-select: none;
    }

    .payment-method:hover {
        border-color: #aac4e8;
        background: white;
    }

    .payment-method.active {
        border-color: var(--primary-blue);
        background: #f0f6ff;
        box-shadow: 0 0 0 3px rgba(21, 101, 192, 0.08);
    }

    .payment-method.active-paypal {
        border-color: #009cde;
        background: #f0f8ff;
        box-shadow: 0 0 0 3px rgba(0, 156, 222, 0.1);
    }

    .payment-method input[type="radio"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: var(--primary-blue);
    }

    .payment-method input[type="radio"]#paymentPaypal {
        accent-color: #009cde;
    }

    .payment-icons i {
        font-size: 1.5rem;
        color: var(--primary-blue);
        opacity: 0.8;
    }

    .paypal-logo {
        height: 26px;
        object-fit: contain;
    }

    /* ─── Info Alert ─── */
    .info-alert {
        background: #e3f2fd;
        border-left: 4px solid var(--primary-blue);
        padding: 16px;
        border-radius: 8px;
        margin: 24px 0;
        transition: all 0.3s ease;
    }

    .info-alert.paypal-alert {
        background: #e8f4fd;
        border-left-color: #009cde;
    }

    /* ─── Terms ─── */
    .terms-checkbox {
        display: flex;
        gap: 12px;
        padding: 16px;
        background: #f8f9fa;
        border-radius: 8px;
        margin: 24px 0;
    }

    .terms-checkbox input[type="checkbox"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
        flex-shrink: 0;
        margin-top: 2px;
    }

    /* ─── Buttons ─── */
    .btn-checkout {
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-blue-dark) 100%);
        border: none;
        color: white;
        padding: 16px 32px;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(21, 101, 192, 0.2);
    }

    .btn-checkout:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(21, 101, 192, 0.3);
        color: white;
    }

    .btn-checkout:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .btn-checkout.btn-paypal {
        background: linear-gradient(135deg, #009cde 0%, #003087 100%);
        box-shadow: 0 4px 12px rgba(0, 48, 135, 0.25);
    }

    .btn-checkout.btn-paypal:hover {
        box-shadow: 0 6px 20px rgba(0, 48, 135, 0.35);
    }

    /* ─── Micro-rappel sous le bouton ─── */
    .btn-micro-reminder {
        display: flex;
        justify-content: center;
        gap: 20px;
        flex-wrap: wrap;
        margin-top: 12px;
    }

    .btn-micro-reminder span {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 0.82rem;
        color: #6c757d;
        font-weight: 500;
    }

    .btn-micro-reminder i {
        color: #28a745;
        font-size: 0.85rem;
    }

    /* ─── Security Badges ─── */
    .security-badges {
        display: flex;
        justify-content: center;
        gap: 16px;
        flex-wrap: wrap;
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid #e9ecef;
    }

    .security-badges span {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.875rem;
        color: #6c757d;
    }

    .security-badges i {
        color: #28a745;
    }

    /* ─── Order Summary ─── */
    .price-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 0;
        border-bottom: 1px dashed #e9ecef;
    }

    .price-row:last-child {
        border-bottom: none;
    }

    .price-label {
        color: #6c757d;
        font-size: 0.95rem;
    }

    .price-value {
        font-weight: 600;
        color: #2c3e50;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 24px 0 0;
        margin-top: 16px;
        border-top: 2px solid #e9ecef;
    }

    .total-label {
        font-size: 1.25rem;
        font-weight: 600;
        color: #2c3e50;
    }

    .total-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--primary-blue);
    }

    .savings-badge {
        background: #d4edda;
        color: #155724;
        padding: 12px 16px;
        border-radius: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 500;
        margin: 16px 0;
    }

    /* ─── Most Popular Badge ─── */
    .most-popular-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        padding: 4px 12px;
        border-radius: 20px;
        margin-bottom: 10px;
    }

    /* ─── Prix barré & ancrage SketchUp ─── */
    .price-anchor-block {
        background: #fff8e1;
        border: 1px solid #ffe082;
        border-radius: 10px;
        padding: 16px;
        margin: 16px 0;
        text-align: center;
    }

    /* ─── Upsell vers Unlimited (package 1 File) ─── */
    .upsell-box {
        background: linear-gradient(135deg, #fff4ec 0%, #ffe8d9 100%);
        border: 2px solid #ff6b35;
        border-radius: 12px;
        padding: 20px;
        margin: 16px 0;
        position: relative;
    }

    .upsell-box::before {
        content: "RECOMMENDED";
        position: absolute;
        top: -10px;
        left: 20px;
        background: #ff6b35;
        color: white;
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        padding: 3px 12px;
        border-radius: 20px;
    }

    .upsell-box .upsell-header {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 700;
        color: #b34700;
        margin-bottom: 8px;
        margin-top: 6px;
    }

    .upsell-box .upsell-header i {
        color: #ff6b35;
        font-size: 1.1rem;
    }

    .upsell-box .upsell-text {
        font-size: 0.88rem;
        color: #5d4422;
        margin-bottom: 14px;
    }

    .upsell-box .upsell-benefits {
        list-style: none;
        padding: 0;
        margin: 0 0 16px;
    }

    .upsell-box .upsell-benefits li {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        font-size: 0.85rem;
        color: #5d4422;
        margin-bottom: 6px;
    }

    .upsell-box .upsell-benefits i {
        color: #28a745;
        margin-top: 2px;
        flex-shrink: 0;
    }

    .upsell-box .btn-upsell {
        display: block;
        text-align: center;
        background: #ff6b35;
        color: white;
        font-weight: 600;
        padding: 12px 16px;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .upsell-box .btn-upsell:hover {
        background: #e85a2a;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
    }

    .price-anchor-block .sketchup-compare {
        font-size: 0.8rem;
        color: #7b6a00;
        margin-bottom: 6px;
        font-weight: 500;
    }

    .price-anchor-block .sketchup-compare strong {
        color: #5d4e00;
    }

    .price-anchor-block .price-crossed {
        font-size: 1rem;
        color: #9e9e9e;
        text-decoration: line-through;
        margin-right: 8px;
    }

    .price-anchor-block .price-final {
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--primary-blue);
    }

    .price-anchor-block .price-economy {
        font-size: 0.8rem;
        color: #28a745;
        font-weight: 600;
        margin-top: 4px;
    }

    @media (max-width: 992px) {
        .summary-card {
            position: relative;
            top: 0;
            margin-top: 30px;
        }

        .checkout-container {
            padding: 40px 0;
        }

        .trust-bar {
            gap: 12px;
        }

        .trust-bar .trust-divider {
            display: none;
        }
    }
</style>
@endpush

@section('content')
<div class="checkout-container" style="margin-top: 100px;">
    <div class="container">

        <!-- Back Link -->
        <a href="{{ route('home') }}" class="back-link">
            <i class="bi bi-arrow-left"></i>
            <span>Back to Home</span>
        </a>

        <!-- Page Title -->
        <div class="text-center mb-5">
            <h1 class="page-title">Complete Your Purchase</h1>
            <p class="text-muted">Choose your preferred payment method</p>
        </div>

        <div class="row g-4">

            <!-- Left Column - Payment Form -->
            <div class="col-lg-7">
                <div class="payment-card">

                    <!-- Trust Bar -->
                    <div class="trust-bar">
                        <div class="trust-item">
                            <span class="stars">★★★★★</span>
                            <span><strong>7,500+</strong> users</span>
                        </div>
                        <div class="trust-divider"></div>
                        <div class="trust-item">
                            <i class="bi bi-check-circle-fill text-success"></i>
                            <span><strong>6,580+</strong> successful conversions</span>
                        </div>
                    </div>

                    <form id="checkoutForm">
                        @csrf

                        <!-- 1. YOUR INFORMATION -->
                        <div class="section-title">1. Your Information</div>
                        <div class="mb-3">
                            <label class="form-label small text-muted">Name</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->username }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-muted">Email</label>
                            <input type="email" class="form-control" value="{{ auth()->user()->email }}" readonly>
                        </div>

                        <!-- 2. PAYMENT METHOD -->
                        <div class="section-title">2. Payment Method</div>

                        {{-- Card --}}
                        <div class="payment-method active" id="method-card" onclick="selectMethod('card')">
                            <div class="d-flex align-items-center gap-3">
                                <input type="radio" name="payment_method" value="card" id="paymentCard" checked>
                                <label for="paymentCard" class="mb-0 flex-grow-1" style="cursor: pointer;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong style="color: #2c3e50;">Credit or Debit Card</strong>
                                            <div class="text-muted small mt-1">Visa, Mastercard, American Express</div>
                                        </div>
                                        <div class="payment-icons d-flex gap-2">
                                            <i class="bi bi-credit-card-2-front"></i>
                                            <i class="bi bi-credit-card"></i>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- PayPal --}}
                        <div class="payment-method" id="method-paypal" onclick="selectMethod('paypal')">
                            <div class="d-flex align-items-center gap-3">
                                <input type="radio" name="payment_method" value="paypal" id="paymentPaypal">
                                <label for="paymentPaypal" class="mb-0 flex-grow-1" style="cursor: pointer;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong style="color: #003087;">PayPal</strong>
                                            <div class="text-muted small mt-1">Pay with your PayPal account</div>
                                        </div>
                                        <img
                                            src="https://www.paypalobjects.com/webstatic/mktg/logo/pp_cc_mark_37x23.jpg"
                                            alt="PayPal"
                                            class="paypal-logo">
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Info alert (change selon la méthode) --}}
                        <div class="info-alert" id="infoAlert">
                            <div class="d-flex gap-2">
                                <i class="bi bi-shield-check" style="color: var(--primary-blue); font-size: 1.2rem;" id="alertIcon"></i>
                                <div>
                                    <strong id="alertTitle">100% Secure Payment</strong>
                                    <p class="mb-0 small text-muted mt-1" id="alertText">
                                        You'll be redirected to Stripe's secure checkout. Your payment information is encrypted and never stored on our servers.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- 3. TERMS & CONDITIONS -->
                        <div class="section-title">3. Terms & Conditions</div>

                        <div class="terms-checkbox">
                            <input type="checkbox" id="agreeTerms" required>
                            <label for="agreeTerms" style="cursor: pointer; font-size: 0.9rem; color: #6c757d;">
                                I agree to the <a href="#" target="_blank" style="color: var(--primary-blue);">Terms of Service</a>
                                and <a href="#" target="_blank" style="color: var(--primary-blue);">Privacy Policy</a>.
                                My files never expire and can be used anytime for conversions.
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-checkout w-100" id="submitBtn">
                            <i class="bi bi-lock-fill me-2" id="btnIcon"></i>
                            <span id="btnText">Unlock Lifetime Access</span>
                        </button>

                        <!-- Micro-rappel sous le bouton -->
                        <div class="btn-micro-reminder">
                            <span><i class="bi bi-check-circle-fill"></i> One-time payment</span>
                            <span><i class="bi bi-check-circle-fill"></i> No subscription</span>
                            <span><i class="bi bi-check-circle-fill"></i> Lifetime access</span>
                        </div>

                        <!-- Security Badges -->
                        <div class="security-badges">
                            <span>
                                <i class="bi bi-shield-check"></i>
                                256-bit SSL
                            </span>
                            <span>
                                <i class="bi bi-patch-check"></i>
                                PCI Compliant
                            </span>
                            <span>
                                <i class="bi bi-headset"></i>
                                24/7 Support
                            </span>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Column - Order Summary -->
            <div class="col-lg-5">
                <div class="summary-card">
                    <h5 class="mb-4" style="font-weight: 600; color: #2c3e50;">Order Summary</h5>

                    <!-- Package Header -->
                    <div class="text-center mb-4 pb-4 border-bottom">
                        {{-- Badge Most Popular (affiché uniquement pour l'offre Unlimited) --}}
                        @if($package->coins >= 9999)
                            <div>
        <span class="most-popular-badge">
            <i class="bi bi-fire"></i>
            Most Popular
        </span>
                            </div>
                        @endif

                        <h3 class="mb-2" style="color: var(--primary-blue); font-weight: 700;">
                            {{ $package->name }}
                        </h3>
                        <p class="text-muted mb-0">{{ $package->description }}</p>
                    </div>

                    @if($package->coins >= 9999)
                        <!-- Bloc ancrage prix (Unlimited seulement) -->
                        <div class="price-anchor-block">
                            <div class="sketchup-compare">
                                vs. SketchUp Pro license: <strong>$399/year</strong>
                            </div>
                            <div>
                                <span class="price-crossed">$49.90</span>
                                <span class="price-final">${{ number_format($package->price, 2) }}</span>
                            </div>
                            <div class="price-economy">You save over 95% vs. SketchUp Pro</div>
                        </div>
                    @else
                        @php
                            $unlimitedPrice = 19.90;
                            $upsellDifference = max($unlimitedPrice - $package->price, 0);
                        @endphp
                            <!-- Upsell vers Unlimited (affiché uniquement pour le package 1 File) -->
                        <div class="upsell-box">
                            <div class="upsell-header">
                                <i class="bi bi-rocket-takeoff-fill"></i>
                                <span>Most users upgrade to Unlimited</span>
                            </div>
                            <p class="upsell-text">
                                For just <strong>$19.90 more</strong>, convert as many SketchUp files as you want — forever, no extra payment ever again.
                            </p>
                            <ul class="upsell-benefits">
                                <li><i class="bi bi-check-circle-fill"></i> Unlimited conversions <span class="text-muted">(vs. just {{ $package->coins }} file here)</span></li>
                                <li><i class="bi bi-check-circle-fill"></i> Priority email support</li>
                                <li><i class="bi bi-check-circle-fill"></i> Complete conversion history</li>
                                <li><i class="bi bi-check-circle-fill"></i> Never buy credits again</li>
                            </ul>
                            <a href="{{ route('coins.checkout', 2) }}" class="btn-upsell">
                                <i class="bi bi-arrow-up-circle me-2"></i>
                                Switch to Unlimited — ${{ number_format($unlimitedPrice, 2) }}
                            </a>
                        </div>
                    @endif

                    <!-- Package Details -->
                    <div class="price-row">
                        <div class="price-label">Conversions included</div>
                        <div class="price-value">
                            @if($package->coins >= 9999)
                                Unlimited
                            @else
                                📄 {{ $package->coins }} {{ $package->coins === 1 ? 'file' : 'files' }}
                            @endif
                        </div>
                    </div>

                    <div class="price-row">
                        <div class="price-label">Price per conversion</div>
                        <div class="price-value">
                            @if($package->coins >= 9999)
                                —
                            @else
                                ${{ number_format($package->price_per_coin, 2) }}
                            @endif
                        </div>
                    </div>

                    <div class="price-row">
                        <div class="price-label">Subtotal</div>
                        <div class="price-value">${{ number_format($package->price, 2) }}</div>
                    </div>

                    <!-- Savings -->
                    @if($package->savings > 0 && $package->coins < 9999)
                    <div class="savings-badge">
                        <span>
                            <i class="bi bi-tag-fill me-2"></i>
                            <strong>Your Savings</strong>
                        </span>
                        <strong>-${{ number_format($package->savings, 2) }}</strong>
                    </div>
                    @endif

                    <!-- Total -->
                    <div class="total-row">
                        <div class="total-label">Total</div>
                        <div class="total-value">${{ number_format($package->price, 2) }}</div>
                    </div>

                    <!-- What You Get -->
                    <div class="mt-4 p-3 bg-light rounded">
                        <h6 class="mb-3 fw-semibold" style="color: #2c3e50;">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            What's Included:
                        </h6>
                        <ul class="list-unstyled mb-0 small">
                            <li class="mb-2 d-flex align-items-start">
                                <i class="bi bi-check2 text-success me-2 mt-1"></i>
                                <span>
                                    @if($package->coins >= 9999)
                                        Unlimited SketchUp file conversions
                                    @else
                                        {{ $package->coins }} SketchUp file {{ $package->coins === 1 ? 'conversion' : 'conversions' }}
                                    @endif
                                </span>
                            </li>
                            <li class="mb-2 d-flex align-items-start">
                                <i class="bi bi-check2 text-success me-2 mt-1"></i>
                                <span>Never expires - use anytime</span>
                            </li>
                            <li class="mb-2 d-flex align-items-start">
                                <i class="bi bi-check2 text-success me-2 mt-1"></i>
                                <span>All SketchUp versions (2017-2026)</span>
                            </li>
                            <li class="mb-2 d-flex align-items-start">
                                <i class="bi bi-check2 text-success me-2 mt-1"></i>
                                <span>Files up to 100 MB each</span>
                            </li>
                            <li class="mb-0 d-flex align-items-start">
                                <i class="bi bi-check2 text-success me-2 mt-1"></i>
                                <span>Priority email support</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Trust Badge -->
                    <div class="text-center mt-4 pt-3 border-top">
                        <div class="d-flex align-items-center justify-content-center gap-3 text-muted small flex-wrap" id="trustBadge">
                            <span class="d-flex align-items-center gap-1">
                                <i class="bi bi-shield-lock-fill" style="color: var(--primary-blue);"></i>
                                Powered by <strong class="ms-1">Stripe</strong>
                            </span>
                            <span class="text-muted">or</span>
                            <span class="d-flex align-items-center gap-1">
                                <i class="bi bi-shield-lock-fill" style="color: #009cde;"></i>
                                <strong>PayPal</strong>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    // URLs des deux gateways
    const stripeUrl = "{{ route('coins.checkout.process', $package) }}";
    const paypalUrl = "{{ route('coins.paypal.create', $package) }}";

    function selectMethod(method) {
        // Cocher le bon radio
        document.getElementById(method === 'card' ? 'paymentCard' : 'paymentPaypal').checked = true;

        // Reset toutes les cards
        document.getElementById('method-card').classList.remove('active', 'active-paypal');
        document.getElementById('method-paypal').classList.remove('active', 'active-paypal');

        const btn    = document.getElementById('submitBtn');
        const alert  = document.getElementById('infoAlert');
        const btnTxt = document.getElementById('btnText');
        const btnIco = document.getElementById('btnIcon');

        if (method === 'paypal') {
            document.getElementById('method-paypal').classList.add('active-paypal');

            // Alert PayPal
            alert.classList.add('paypal-alert');
            document.getElementById('alertIcon').style.color = '#009cde';
            document.getElementById('alertTitle').textContent = 'Pay securely with PayPal';
            document.getElementById('alertText').textContent =
                "You'll be redirected to PayPal's secure checkout. No card details needed — pay with your PayPal balance or linked card.";

            // Bouton PayPal
            btn.classList.add('btn-paypal');
            btnIco.className = 'bi bi-paypal me-2';
            btnTxt.textContent = 'Continue with PayPal';

        } else {
            document.getElementById('method-card').classList.add('active');

            // Alert Stripe
            alert.classList.remove('paypal-alert');
            document.getElementById('alertIcon').style.color = 'var(--primary-blue)';
            document.getElementById('alertTitle').textContent = '100% Secure Payment';
            document.getElementById('alertText').textContent =
                "You'll be redirected to Stripe's secure checkout. Your payment information is encrypted and never stored on our servers.";

            // Bouton Card
            btn.classList.remove('btn-paypal');
            btnIco.className = 'bi bi-lock-fill me-2';
            btnTxt.textContent = 'Unlock Lifetime Access';
        }
    }

    // Init par défaut
    selectMethod('card');

    // Soumission du formulaire
   document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        e.preventDefault();

        if (!document.getElementById('agreeTerms').checked) {
            alert('⚠️ Please accept the terms and conditions to continue.');
            return;
        }

        const method = document.querySelector('input[name="payment_method"]:checked').value;
        const btn    = document.getElementById('submitBtn');

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>'
            + (method === 'paypal' ? 'Redirecting to PayPal...' : 'Redirecting to Stripe...');

        if (method === 'paypal') {
            window.location.href = paypalUrl;
        } else {
            // Créer un formulaire POST dynamique pour Stripe
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = stripeUrl;

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = document.querySelector('input[name="_token"]').value;

            form.appendChild(csrf);
            document.body.appendChild(form);
            form.submit();
        }
    });

    // Afficher le message d'annulation si retour depuis PayPal/Stripe
    @if(session('error'))
        const errorEl = document.createElement('div');
        errorEl.className = 'alert alert-warning alert-dismissible fade show mt-3';
        errorEl.innerHTML = `{{ session('error') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
        document.querySelector('.payment-card').prepend(errorEl);
    @endif
</script>
@endpush
@endsection
