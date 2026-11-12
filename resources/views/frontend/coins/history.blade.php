@extends('frontend.layout.app')

@section('title', 'Coin History')

@push('styles')
<style>
    :root {
        --primary-blue: #4F5FD6;
        --secondary-blue: #6B7AE5;
        --light-blue: #99AAF5;
        --success-green: #57F287;
        --danger-red: #ED4245;
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


    .history-hero {
        background: #3150bc;
        padding: 160px 0 100px;
        color: white;
        text-align: center;
        position: relative;
    }

    .history-hero::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 60px;
        background: #f8f9fa;
        border-radius: 40px 40px 0 0;
    }

    .history-hero h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 15px;
        position: relative;
        z-index: 1;
    }

    .history-hero p {
        font-size: 1.1rem;
        opacity: 0.95;
        position: relative;
        z-index: 1;
    }

    .stats-overview {
        background: white;
        border-radius: 20px;
        padding: 40px 30px;
        box-shadow: 0 4px 20px rgba(49, 80, 188, 0.1);
        margin: -60px auto 40px;
        max-width: 1200px;
        position: relative;
        z-index: 2;
    }

    .stat-box {
        text-align: center;
        padding: 20px 15px;
    }

    .stat-number {
        font-size: 3rem;
        font-weight: 700;
        color: #3150bc;
        margin-bottom: 10px;
    }

    .stat-label {
        color: var(--gray-text);
        font-size: 0.95rem;
        font-weight: 500;
    }

    .history-card {
        background: white;
        border-radius: 16px;
        padding: 35px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark-text);
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-title i {
        color: #3150bc;
    }

    .purchase-item {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .purchase-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(49, 80, 188, 0.1);
        border-color: #3150bc;
    }

    .purchase-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .purchase-title {
        font-weight: 600;
        color: var(--dark-text);
        font-size: 1.1rem;
    }

    .purchase-date {
        color: var(--gray-text);
        font-size: 0.85rem;
        margin-top: 5px;
    }

    .status-badge {
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .status-completed {
        background: #d4edda;
        color: #155724;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-failed {
        background: #f8d7da;
        color: #721c24;
    }

    .purchase-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }

    .info-box {
        text-align: center;
        padding: 12px;
        background: white;
        border-radius: 8px;
    }

    .info-label {
        font-size: 0.8rem;
        color: var(--gray-text);
        margin-bottom: 5px;
    }

    .info-value {
        font-weight: 600;
        color: var(--dark-text);
        font-size: 1rem;
    }

    .transaction-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 18px;
        border-radius: 12px;
        margin-bottom: 12px;
        border: 1px solid #f0f0f0;
        background: white;
        transition: all 0.3s ease;
    }

    .transaction-item:hover {
        background: #f8f9fa;
        transform: translateX(5px);
        border-color: #3150bc;
    }

    .transaction-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }

    .transaction-icon.credit {
        background: var(--success-green);
        color: white;
    }

    .transaction-icon.debit {
        background: var(--danger-red);
        color: white;
    }

    .transaction-content {
        flex: 1;
    }

    .transaction-title {
        font-weight: 600;
        color: var(--dark-text);
        margin-bottom: 3px;
    }

    .transaction-date {
        font-size: 0.85rem;
        color: var(--gray-text);
    }

    .transaction-amount {
        text-align: right;
    }

    .amount-value {
        font-size: 1.4rem;
        font-weight: 700;
    }

    .amount-value.credit {
        color: var(--success-green);
    }

    .amount-value.debit {
        color: var(--danger-red);
    }

    .balance-text {
        font-size: 0.8rem;
        color: var(--gray-text);
        margin-top: 3px;
        background: #f8f9fa;
        padding: 4px 10px;
        border-radius: 10px;
        display: inline-block;
    }

    .empty-state {
        text-align: center;
        padding: 80px 20px;
    }

    .empty-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto 30px;
        background: rgba(49, 80, 188, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3.5rem;
        color: #3150bc;
    }

    .empty-state h4 {
        color: var(--dark-text);
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 12px;
    }

    .empty-state p {
        color: var(--gray-text);
        font-size: 1rem;
        margin-bottom: 30px;
    }

    .btn-buy-coins {
        background: #3150bc;
        color: white;
        border: none;
        padding: 14px 35px;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 12px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .btn-buy-coins:hover {
        background: #2a44a3;
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(49, 80, 188, 0.3);
        color: white;
    }

    .btn-buy-coins i {
        font-size: 1.1rem;
    }

    @media (max-width: 768px) {
        .history-hero {
            padding: 140px 20px 80px;
        }

        .history-hero h1 {
            font-size: 2rem;
        }

        .stats-overview {
            padding: 30px 20px;
            margin: -30px 15px 30px;
        }

        .stat-number {
            font-size: 2.5rem;
        }

        .history-card {
            padding: 25px 20px;
        }

        .purchase-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .transaction-item {
            flex-wrap: wrap;
        }

        .transaction-amount {
            width: 100%;
            text-align: left;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #f0f0f0;
        }

        .empty-icon {
            width: 100px;
            height: 100px;
            font-size: 3rem;
        }

        .empty-state {
            padding: 60px 20px;
        }
    }
</style>
@endpush


@section('content')
    <!-- Hero Section -->
    <section class="history-hero">
        <div class="container">
            <h1>📊 Conversion History</h1>
            <p>Review all your file purchases and usage</p>
        </div>
    </section>

    <div class="container">
        <!-- Stats Overview -->
        <div class="stats-overview" data-aos="fade-up">
            <div class="row">
                <div class="col-md-4">
                    <div class="stat-box">
                        <div class="stat-number">{{ auth()->user()->display_balance }}</div>
                        <div class="stat-label">Available Files</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-box">
                        <div class="stat-number">{{ $purchases->where('status', 'completed')->sum('coins_purchased') }}</div>
                        <div class="stat-label">Total Files Purchased</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-box">
                        <div class="stat-number">{{ $transactions->where('type', 'debit')->count() }}</div>
                        <div class="stat-label">Conversions Completed</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Purchase History -->
        <div class="history-card" data-aos="fade-up">
            <h2 class="card-title">
                <i class="bi bi-credit-card"></i>
                Purchase History
            </h2>

            @if($purchases->count() > 0)
                @foreach($purchases as $purchase)
                <div class="purchase-item">
                    <div class="purchase-header">
                        <div>
                            <div class="purchase-title">{{ $purchase->coinPackage->name }}</div>
                            <div class="purchase-date">
                                {{ $purchase->created_at->format('M d, Y \a\t H:i') }}
                            </div>
                        </div>
                        <span class="status-badge status-{{ $purchase->status }}">
                            @if($purchase->status === 'completed')
                                <i class="bi bi-check-circle"></i> Completed
                            @elseif($purchase->status === 'pending')
                                <i class="bi bi-clock"></i> Pending
                            @else
                                <i class="bi bi-x-circle"></i> Failed
                            @endif
                        </span>
                    </div>

                    <div class="purchase-info">
                        <div class="info-box">
                            <div class="info-label">Files</div>
                            <div class="info-value">
                                @if($purchase->coins_purchased >= 9999)
                                    ♾️ Unlimited
                                @else
                                    📄 {{ $purchase->coins_purchased }}
                                @endif
                            </div>
                        </div>
                        <div class="info-box">
                            <div class="info-label">Amount</div>
                            <div class="info-value">${{ number_format($purchase->amount_paid, 2) }}</div>
                        </div>
                        <div class="info-box">
                            <div class="info-label">Price/File</div>
                            <div class="info-value">
                                @if($purchase->coins_purchased >= 9999)
                                    —
                                @else
                                    ${{ number_format($purchase->amount_paid / $purchase->coins_purchased, 2) }}
                                @endif
                            </div>
                        </div>
                        <div class="info-box">
                            <div class="info-label">Reference</div>
                            <div class="info-value">#{{ $purchase->id }}</div>
                        </div>
                    </div>
                </div>
                @endforeach

                @if($purchases->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $purchases->links() }}
                </div>
                @endif
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="bi bi-bag-x"></i>
                    </div>
                    <h4>No purchases yet</h4>
                    <p>Your file purchases will appear here</p>
                    <button class="btn-buy-coins" data-bs-toggle="modal" data-bs-target="#pricingModal">
                        <i class="bi bi-plus-circle"></i>
                        <span>Buy Files</span>
                    </button>
                </div>
            @endif
        </div>

        <!-- Transaction History -->
        <div class="history-card" data-aos="fade-up">
            <h2 class="card-title">
                <i class="bi bi-list-ul"></i>
                Transaction History
            </h2>

            @if($transactions->count() > 0)
                @foreach($transactions as $transaction)
                <div class="transaction-item">
                    <div class="transaction-icon {{ $transaction->type }}">
                        @if($transaction->type === 'credit')
                            <i class="bi bi-plus-lg"></i>
                        @else
                            <i class="bi bi-dash-lg"></i>
                        @endif
                    </div>

                    <div class="transaction-content">
                        <div class="transaction-title">{{ $transaction->description }}</div>
                        <div class="transaction-date">
                            {{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y \a\t H:i') }}
                        </div>
                    </div>

                    <div class="transaction-amount">
                        <div class="amount-value {{ $transaction->type }}">
                            {{ $transaction->type === 'credit' ? '+' : '' }}{{ $transaction->coins >= 9999 ? '∞' : $transaction->coins }}
                        </div>
                        <div class="balance-text">
                            Balance: {{ $transaction->balance_after >= 9999 ? 'Unlimited' : $transaction->balance_after . ' file(s)' }}
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <h4>No transactions yet</h4>
                    <p>Your conversion history will appear here</p>
                </div>
            @endif
        </div>
    </div>
@endsection
