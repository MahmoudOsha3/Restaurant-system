@extends('layout.site.app')

@section('title', 'فشل عملية الدفع - شيخ المندي')

@section('css')
<style>
    /* نستخدم نفس استايل الصفحة السابقة مع تغيير الألوان */
    .status-card.failed { border-color: #dc3545; box-shadow: 0 10px 30px rgba(220, 53, 69, 0.1); }
    .icon-box.failed { background: rgba(220, 53, 69, 0.1); color: #dc3545; }

    .retry-options {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-top: 20px;
    }
    .btn-outline {
        border: 1px solid #333;
        color: #fff;
        background: transparent;
    }
    .btn-outline:hover { border-color: var(--gold); color: var(--gold); }
</style>
@endsection

@section('content')
<div class="status-container">
    <div class="status-card failed animate-up">
        <div class="icon-box failed">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h2 class="status-title">عذراً، لم يكتمل الدفع</h2>
        <p class="status-msg">
            يبدو أن هناك مشكلة حدثت أثناء معالجة العملية. <br>
            لا تقلق، يمكنك المحاولة مرة أخرى أو اختيار وسيلة دفع بديلة.
        </p>

        <div class="retry-options">
            <a href="{{ route('orders.checkout') }}" class="action-btn">
                محاولة الدفع مرة أخرى
            </a>
        </div>
    </div>
</div>
@endsection
