@extends('layout.site.app')

@section('title', 'تم الدفع بنجاح - شيخ المندي')

@section('css')
<style>
    .status-container {
        padding: 150px 20px;
        text-align: center;
        background: #0a0a0a;
        min-height: 100vh;
    }
    .status-card {
        max-width: 500px;
        margin: 0 auto;
        background: #151515;
        padding: 40px;
        border-radius: 20px;
        border: 1px solid #28a745; /* إطار أخضر خفيف */
        box-shadow: 0 10px 30px rgba(40, 167, 69, 0.1);
    }
    .icon-box {
        width: 80px;
        height: 80px;
        background: rgba(40, 167, 69, 0.1);
        color: #28a745;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        margin: 0 auto 20px;
    }
    .status-title { color: #fff; margin-bottom: 10px; font-family: 'Reem Kufi', sans-serif; }
    .status-msg { color: #888; margin-bottom: 30px; line-height: 1.6; }

    .action-btn {
        display: inline-block;
        padding: 12px 30px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: bold;
        transition: 0.3s;
        background: var(--gold);
        color: #000;
    }
    .action-btn:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(193, 155, 118, 0.3); }
</style>
@endsection

@section('content')
<div class="status-container">
    <div class="status-card animate-up">
        <div class="icon-box">
            <i class="fas fa-check-circle"></i>
        </div>
        <h2 class="status-title">دفع ملكي ناجح!</h2>
        <p class="status-msg">
            تم استلام مبلغ الطلب بنجاح. <br>
            بدأ طهاتنا الآن في تجهيز وجبتك بكل حب وإتقان.
        </p>
        <div class="order-details-mini" style="background: #0d0d0d; padding: 15px; border-radius: 10px; margin-bottom: 25px;">
            <span style="color: #666; font-size: 0.9rem;">رقم الطلب:</span>
            <span style="color: var(--gold); font-weight: bold;">#{{ $order_number }}</span>
        </div>
        <a href="{{ route('orders.checkout') }}" class="action-btn">تتبع طلباتك</a>
    </div>
</div>
@endsection
