@extends('layout.site.app')

@section('title', 'طلباتي - شيخ المندي')

@section('css')
<style>
    .orders-page { padding: 120px 20px 60px; background: #0a0a0a; min-height: 100vh; }
    .container { max-width: 900px; margin: 0 auto; }

    .order-card {
        background: #151515;
        border-radius: 20px;
        border: 1px solid #333;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }

    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #222;
        padding-bottom: 20px;
        margin-bottom: 20px;
    }
    .order-id { color: var(--gold); font-weight: bold; font-size: 1.2rem; }

    /* تنسيق قائمة الأصناف */
    .order-items-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-bottom: 25px;
    }
    .item-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px;
        background: #0d0d0d;
        border-radius: 12px;
        border: 1px solid #222;
    }
    .item-main-info { display: flex; align-items: center; gap: 15px; }
    .item-img { width: 55px; height: 55px; border-radius: 8px; object-fit: cover; }
    .item-name { color: #fff; font-weight: bold; font-size: 0.95rem; }
    .item-price-qty { color: #888; font-size: 0.85rem; margin-top: 4px; }
    .item-subtotal { color: var(--gold); font-weight: bold; }

    /* قسم تفاصيل الفاتورة */
    .order-billing-details {
        background: #1a1a1a;
        padding: 20px;
        border-radius: 15px;
        margin-bottom: 20px;
    }
    .bill-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        color: #bbb;
        font-size: 0.9rem;
    }
    .bill-row.grand-total {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px dashed #444;
        color: var(--gold);
        font-size: 1.3rem;
        font-weight: bold;
    }

    .shipping-info {
        color: #888;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
    }

    .order-footer {
        display: flex;
        justify-content: flex-end;
    }

    .pay-now-btn {
        background: var(--gold);
        color: #000;
        padding: 12px 30px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: bold;
        transition: 0.3s;
    }
</style>
@endsection

@section('content')
<div class="orders-page">
    <div class="container">
        <h2 style="color: #fff; margin-bottom: 40px; text-align: right; font-family: 'Reem Kufi', sans-serif;">سجل طلباتك الملكية</h2>

        @forelse($orders as $order)
        <div class="order-card animate-up">
            <div class="order-header">
                <div>
                    <span class="order-id">طلب رقم #{{ $order->order_number }}</span>
                    <p style="color: #666; font-size: 0.8rem; margin-top: 5px;">تاريخ الطلب: {{ $order->created_at->format('Y-m-d') }}</p>
                </div>
                <span class="order-status {{ $order->status == 'pending' ? 'status-pending' : 'status-completed' }}">
                    {{ $order->status == 'pending' ? 'قيد الانتظار' : 'تم التوصيل' }}
                </span>
            </div>

            <div class="order-items-list">
                @foreach($order->orderItems as $item)
                <div class="item-row">
                    <div class="item-main-info">
                        <img src="{{ $item->meal->image_url }}" alt="{{ $item->meal->title }}" class="item-img">
                        <div>
                            <div class="item-name">{{ $item->meal->title }}</div>
                            <div class="item-price-qty">{{ $item->price }} ج.م × {{ $item->quantity }}</div>
                        </div>
                    </div>
                    <div class="item-subtotal">
                        {{ $item->price * $item->quantity }} ج.م
                    </div>
                </div>
                @endforeach
            </div>

            <div class="shipping-info">
                <i class="fas fa-truck-loading"></i>
                توصيل إلى: {{ $order->user->address }}, {{ $order->user->city }}
            </div>

            <div class="order-billing-details">
                <div class="bill-row">
                    <span>إجمالي الأصناف</span>
                    <span>{{ $order->subtotal }} ج.م</span>
                </div>
                <div class="bill-row">
                    <span>الضريبة (3%)</span>
                    <span>{{ number_format($order->tax , 2) }} ج.م</span>
                </div>
                <div class="bill-row">
                    <span>رسوم التوصيل</span>
                    <span>{{ number_format($order->delivery_fee , 2) }} ج.م</span>
                </div>
                <div class="bill-row grand-total">
                    <span>الإجمالي النهائي</span>
                    <span>{{ $order->total }} ج.م</span>
                </div>
            </div>

            <div class="order-footer">
                @if($order->payment_status == 'unpaid')
                <form action="{{ route('order.payment' , $order->id ) }}" method="post">
                    @csrf
                    <button class="pay-now-btn" type="submit">
                        <i class="fas fa-credit-card"></i> إتمام الدفع الآن
                    </button>
                </form>
                @else
                    <span class="paid-badge" style="color: #28a745; font-weight: bold;">
                        <i class="fas fa-check-double"></i> تم سداد الفاتورة
                    </span>
                @endif
            </div>
        </div>
        @empty
        <div style="text-align: center; color: #888; padding: 100px 20px;">
            <i class="fas fa-receipt" style="font-size: 5rem; color: #222; margin-bottom: 20px;"></i>
            <h3>لا توجد طلبات في سجلاتنا حتى الآن</h3>
            <a href="/#menu" style="color: var(--gold); text-decoration: none; margin-top: 15px; display: inline-block;">استعرض القائمة الملكية من هنا</a>
        </div>
        @endforelse
    </div>
</div>
@endsection
