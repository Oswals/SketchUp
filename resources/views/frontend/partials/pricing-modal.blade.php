<!-- ==================== MODAL PRICING (Minimal) ==================== -->
<div class="modal fade" id="pricingModal" tabindex="-1" aria-labelledby="pricingModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 15px; border: none;">
            <div class="modal-header border-bottom" style="background: #3150bc; color: white;">
                <div>
                    <h5 class="modal-title fw-bold mb-0" id="pricingModalLabel">Buy Files</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">

                <!-- Info utilisateur -->
                @auth
                <div class="alert alert-light border mb-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-wallet2 fs-4 me-3" style="color: #3150bc;"></i>
                        <div>
                            <strong>Current balance: {{ auth()->user()->display_balance }}</strong>
                            <p class="mb-0 small text-muted">1 file = 1 conversion • No expiration • Secure payment</p>
                        </div>
                    </div>
                </div>
                @endauth

                <!-- Packages -->
                <div class="row g-3 justify-content-center">

                    <!-- Package 1 File -->
                    <div class="col-md-5">
                        <div class="card h-100 border text-center">
                            <div class="card-body p-4">
                                <i class="bi bi-file-earmark fs-1 mb-3 d-block" style="color: #3150bc;"></i>
                                <h5 class="fw-bold mb-2">1 File</h5>
                                <p class="text-muted small mb-3">For one-time needs</p>
                                <div class="mb-3">
                                    <span class="fs-2 fw-bold" style="color: #3150bc;">$0.90</span>
                                </div>
                                @auth
                                    <a href="{{ route('coins.checkout', 1) }}" class="btn btn-outline-primary w-100">Buy Now</a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-outline-primary w-100">Login to Buy</a>
                                @endauth
                            </div>
                        </div>
                    </div>

                    <!-- Package Unlimited -->
                    <div class="col-md-5">
                        <div class="card h-100 border-primary text-center" style="border-width: 2px;">
                            <div class="card-body p-4">
                                <span class="badge bg-primary mb-2">Best Value</span>
                                <i class="bi bi-infinity fs-1 mb-3 d-block" style="color: #3150bc;"></i>
                                <h5 class="fw-bold mb-2">Unlimited</h5>
                                <p class="text-muted small mb-3">For professionals</p>
                                <div class="mb-2">
                                    <span class="fs-2 fw-bold" style="color: #3150bc;">$19.90</span>
                                </div>
                                <div class="small text-muted mb-3">One-time • Never expires</div>
                                @auth
                                    <a href="{{ route('coins.checkout', 2) }}" class="btn btn-primary w-100">Buy Now</a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-primary w-100">Login to Buy</a>
                                @endauth
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Message informatif -->
                <div class="text-center text-muted mt-4 small">
                    <i class="bi bi-shield-check text-success me-1"></i>
                    Secure payment by Stripe
                </div>

            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    #pricingModal .card {
        transition: all 0.3s ease;
        border-radius: 12px;
    }

    #pricingModal .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(49, 80, 188, 0.15);
    }

    #pricingModal .border-primary {
        border-color: #3150bc !important;
    }

    #pricingModal .btn-primary {
        background: #3150bc;
        border-color: #3150bc;
        transition: all 0.3s ease;
    }

    #pricingModal .btn-primary:hover {
        background: #2a44a3;
        border-color: #2a44a3;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(49, 80, 188, 0.3);
    }

    #pricingModal .btn-outline-primary {
        color: #3150bc;
        border-color: #3150bc;
        transition: all 0.3s ease;
    }

    #pricingModal .btn-outline-primary:hover {
        background: #3150bc;
        border-color: #3150bc;
        color: white;
        transform: translateY(-2px);
    }

    #pricingModal .badge.bg-primary {
        background: #3150bc !important;
    }
</style>
@endpush
