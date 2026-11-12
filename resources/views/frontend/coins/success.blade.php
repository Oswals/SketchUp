@extends('frontend.layout.app')

@section('title', 'Payment Successful!')

@push('styles')
<style>
    :root {
        --primary-blue: #3150bc;
        --secondary-blue: #6B7AE5;
        --light-blue: #99AAF5;
        --success-green: #57F287;
        --warning-yellow: #FEE75C;
        --dark-text: #2C3E50;
        --gray-text: #6c757d;
    }

    .header {
        color: var(--default-color);
        background-color: white;
        padding: 20px 0;
        transition: all 0.5s;
        z-index: 997;
    }

    .success-section {
        min-height: 100vh;
        padding: 160px 0 100px;
        background: #3150bc;
        position: relative;
    }

    .success-section::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 200px;
        background: #f8f9fa;
        border-radius: 40px 40px 0 0;
    }

    .success-card {
        background: white;
        border-radius: 24px;
        padding: 50px 40px;
        box-shadow: 0 10px 40px rgba(49, 80, 188, 0.15);
        max-width: 650px;
        margin: 0 auto;
        animation: slideUp 0.5s ease;
        position: relative;
        z-index: 2;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .status-icon {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 30px;
        animation: scaleIn 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    .status-icon.success {
        background: var(--success-green);
    }

    .status-icon.warning {
        background: var(--warning-yellow);
    }

    .status-icon.pending {
        background: var(--primary-blue);
        animation: pulse 2s infinite;
    }

    @keyframes scaleIn {
        0% {
            transform: scale(0);
            opacity: 0;
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    .status-icon i {
        font-size: 3rem;
        color: white;
    }

    .success-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark-text);
        margin-bottom: 15px;
        text-align: center;
    }

    .success-subtitle {
        font-size: 1.1rem;
        color: var(--gray-text);
        margin-bottom: 35px;
        text-align: center;
    }

    .purchase-summary {
        background: #f8f9fa;
        border-radius: 16px;
        padding: 25px;
        margin: 30px 0;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #e9ecef;
    }

    .summary-item:last-child {
        border-bottom: none;
    }

    .summary-label {
        color: var(--gray-text);
        font-size: 0.95rem;
    }

    .summary-value {
        color: var(--dark-text);
        font-weight: 600;
        font-size: 1rem;
    }

    .file-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: var(--primary-blue);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
    }

    .balance-display {
        background: var(--primary-blue);
        color: white;
        text-align: center;
        padding: 30px;
        border-radius: 16px;
        margin: 25px 0;
    }

    .balance-label {
        font-size: 0.95rem;
        opacity: 0.95;
        margin-bottom: 10px;
    }

    .balance-amount {
        font-size: 3rem;
        font-weight: 700;
        margin: 10px 0;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        margin-top: 35px;
        flex-wrap: wrap;
    }

    .btn-action {
        flex: 1;
        min-width: 200px;
        padding: 14px 30px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        text-align: center;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-primary-action {
        background: var(--primary-blue);
        color: white;
        border: none;
    }

    .btn-primary-action:hover {
        background: #2a44a3;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(49, 80, 188, 0.3);
        color: white;
    }

    .btn-secondary-action {
        background: white;
        color: var(--primary-blue);
        border: 2px solid var(--primary-blue);
    }

    .btn-secondary-action:hover {
        background: var(--primary-blue);
        color: white;
    }

    .info-alert {
        background: #e7f3ff;
        border-left: 4px solid var(--primary-blue);
        padding: 15px 20px;
        border-radius: 8px;
        margin: 25px 0;
        font-size: 0.95rem;
        color: var(--dark-text);
    }

    .warning-alert {
        background: #fff8e1;
        border-left: 4px solid var(--warning-yellow);
        padding: 15px 20px;
        border-radius: 8px;
        margin: 25px 0;
        font-size: 0.95rem;
        color: #856404;
    }

    .email-notice {
        text-align: center;
        margin-top: 30px;
        padding-top: 25px;
        border-top: 1px solid #e9ecef;
        color: var(--gray-text);
        font-size: 0.9rem;
    }

    @media (max-width: 768px) {
        .success-section {
            padding: 140px 20px 80px;
        }

        .success-card {
            padding: 35px 25px;
        }

        .success-title {
            font-size: 1.6rem;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-action {
            width: 100%;
            min-width: auto;
        }

        .balance-amount {
            font-size: 2.5rem;
        }
    }
</style>
@endpush

@section('content')
<section class="success-section">
    <div class="container">
        <div class="success-card">

            @if(isset($coins_credited) && $coins_credited)
                {{-- ✅ Payment successful and files credited --}}
                <div class="status-icon success">
                    <i class="bi bi-check-lg"></i>
                </div>
                <h1 class="success-title">🎉 Payment Successful!</h1>
                <p class="success-subtitle">Your conversions have been added to your account</p>

            @elseif(isset($credit_error) && $credit_error)
                {{-- ⚠️ Payment successful but credit error --}}
                <div class="status-icon warning">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <h1 class="success-title">⏳ Processing Payment...</h1>
                <p class="success-subtitle">Your payment was successful, we're processing your conversions</p>
                
                <div class="warning-alert">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Don't worry!</strong> Your conversions will be added automatically. If they don't appear within 5 minutes, contact support.
                </div>

            @elseif($purchase && $purchase->status === 'completed')
                {{-- ✅ Already completed purchase --}}
                <div class="status-icon success">
                    <i class="bi bi-check-lg"></i>
                </div>
                <h1 class="success-title">✅ Already Processed!</h1>
                <p class="success-subtitle">This purchase was already completed</p>

            @else
                {{-- ⏳ Pending payment --}}
                <div class="status-icon pending">
                    <i class="bi bi-clock"></i>
                </div>
                <h1 class="success-title">⏳ Confirming Payment...</h1>
                <p class="success-subtitle">Please wait while we confirm your payment</p>

                <div class="info-alert">
                    <i class="bi bi-hourglass-split me-2"></i>
                    This page will refresh automatically...
                </div>

                <script>
                    setTimeout(() => location.reload(), 3000);
                </script>
            @endif

            {{-- Purchase Details --}}
            @if(isset($purchase))
            <div class="purchase-summary">
                <div class="summary-item">
                    <span class="summary-label">Files Purchased</span>
                    <span class="summary-value">
                        <span class="file-badge">
                            @if($purchase->coins_purchased >= 9999)
                                ♾️ Unlimited
                            @else
                                📄 {{ $purchase->coins_purchased }} {{ $purchase->coins_purchased === 1 ? 'file' : 'files' }}
                            @endif
                        </span>
                    </span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Amount Paid</span>
                    <span class="summary-value">${{ number_format($purchase->amount_paid, 2) }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Date</span>
                    <span class="summary-value">
                        {{ $purchase->completed_at ? $purchase->completed_at->format('M d, Y \a\t H:i') : now()->format('M d, Y \a\t H:i') }}
                    </span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Reference</span>
                    <span class="summary-value">#{{ $purchase->id }}</span>
                </div>
            </div>
            @endif

            {{-- Current Balance --}}
            @if(!isset($credit_error) || !$credit_error)
            <div class="balance-display">
                <div class="balance-label">Available Conversions</div>
                    <div class="balance-amount">{{ auth()->user()->display_balance }}</div>
                    <div class="balance-label">
                        @if(auth()->user()->coin_balance >= 9999)
                            Unlimited files ready to convert ♾️
                        @else
                            {{ auth()->user()->coin_balance === 1 ? 'file' : 'files' }} ready to convert 📄
                        @endif
                    </div>
            </div>
            @endif

            {{-- Info Message --}}
            @if(isset($coins_credited) && $coins_credited || ($purchase && $purchase->status === 'completed'))
            <div class="info-alert">
                <i class="bi bi-info-circle me-2"></i>
                Your conversions never expire! Use them anytime to convert your files.
            </div>
            @endif

            {{-- Action Buttons --}}
            <div class="action-buttons">
                @if(isset($coins_credited) && $coins_credited || ($purchase && $purchase->status === 'completed'))
                    <a href="{{ route('home') }}" class="btn-action btn-primary-action">
                        <i class="bi bi-upload"></i>
                        <span>Convert a File</span>
                    </a>
                    <a href="{{ route('conversion.result') }}" class="btn-action btn-secondary-action">
                        <i class="bi bi-clock-history"></i>
                        <span>View History</span>
                    </a>
                @else
                    <a href="{{ route('home') }}" class="btn-action btn-primary-action">
                        <i class="bi bi-house"></i>
                        <span>Back to Home</span>
                    </a>
                    <a href="{{ route('conversion.result') }}" class="btn-action btn-secondary-action">
                        <i class="bi bi-grid"></i>
                        <span>My Conversions</span>
                    </a>
                @endif
            </div>

            {{-- Email Confirmation --}}
            @if(isset($coins_credited) && $coins_credited || ($purchase && $purchase->status === 'completed'))
            <div class="email-notice">
                <i class="bi bi-envelope-check me-2"></i>
                A confirmation email has been sent to <strong>{{ auth()->user()->email }}</strong>
            </div>
            @endif
        </div>
    </div>
</section>
@endsection
