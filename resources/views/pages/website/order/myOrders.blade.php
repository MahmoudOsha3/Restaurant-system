@extends('layout.site.app')

@section('title', 'طلباتي - شيخ المندي')

@section('css')
<style>
    /* التنسيقات الأصلية بتاعتك */
    .orders-page { padding: 120px 20px 60px; background: #0a0a0a; min-height: 100vh; }
    .container { max-width: 900px; margin: 0 auto; }
    .order-card { background: #151515; border-radius: 20px; border: 1px solid #333; padding: 30px; margin-bottom: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); position: relative; }
    .order-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #222; padding-bottom: 20px; margin-bottom: 20px; }
    .order-id { color: var(--gold); font-weight: bold; font-size: 1.2rem; }

    /* تنسيق حالات الطلب الجديد */
    .status-badge { padding: 5px 12px; border-radius: 8px; font-size: 0.8rem; font-weight: bold; display: inline-flex; align-items: center; gap: 5px; }
    .status-pending { background: #332b00; color: #ffcc00; }
    .status-completed { background: #003311; color: #00ff66; }
    .status-confirmed { background: #003311; color: #ff8533; }
    .status-unpaid { background: #330000; color: #ff4444; }
    .status-paid { background: #001a33; color: #3399ff; }

    /* تنسيق قائمة الأصناف */
    .order-items-list { display: flex; flex-direction: column; gap: 15px; margin-bottom: 25px; }
    .item-row { display: flex; align-items: center; justify-content: space-between; padding: 10px; background: #0d0d0d; border-radius: 12px; border: 1px solid #222; }
    .item-main-info { display: flex; align-items: center; gap: 15px; }
    .item-img { width: 55px; height: 55px; border-radius: 8px; object-fit: cover; }
    .item-name { color: #fff; font-weight: bold; font-size: 0.95rem; }
    .item-price-qty { color: #888; font-size: 0.85rem; margin-top: 4px; }
    .item-subtotal { color: var(--gold); font-weight: bold; }

    .order-billing-details { background: #1a1a1a; padding: 20px; border-radius: 15px; margin-bottom: 20px; }
    .bill-row { display: flex; justify-content: space-between; margin-bottom: 10px; color: #bbb; font-size: 0.9rem; }
    .bill-row.grand-total { margin-top: 15px; padding-top: 15px; border-top: 1px dashed #444; color: var(--gold); font-size: 1.3rem; font-weight: bold; }
    .shipping-info { color: #888; font-size: 0.85rem; display: flex; align-items: center; gap: 8px; margin-bottom: 20px; }
    .order-footer { display: flex; justify-content: flex-end; }

    /* زر الدفع */
    .pay-now-btn { background: var(--gold); color: #000; padding: 12px 30px; border-radius: 10px; border:none; cursor: pointer; font-weight: bold; transition: 0.3s; }
    .pay-now-btn:hover { transform: scale(1.05); }

    /* --- Custom Modal CSS --- */
    .custom-modal {
        display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%;
        background-color: rgba(0,0,0,0.85); backdrop-filter: blur(5px); align-items: center; justify-content: center;
    }
    .modal-content {
        background: #151515; border: 1px solid #444; width: 90%; max-width: 450px;
        padding: 30px; border-radius: 25px; position: relative; color: white; text-align: right;
    }
    .modal-header { font-size: 1.5rem; color: var(--gold); margin-bottom: 20px; font-weight: bold; }

    .payment-option {
        display: block; background: #0d0d0d; border: 2px solid #222; padding: 15px;
        margin-bottom: 15px; border-radius: 15px; cursor: pointer; transition: 0.3s;
    }
    .payment-option:hover { border-color: var(--gold); }
    .payment-option input { display: none; }
    .payment-option input:checked + .option-box { border-color: var(--gold); color: var(--gold); }
    .option-box { display: flex; align-items: center; gap: 15px; }
    .option-box img { width: 40px; }

    .confirm-pay-btn {
        width: 100%; background: var(--gold); color: #000; border: none; padding: 15px;
        border-radius: 12px; font-weight: bold; cursor: pointer; margin-top: 10px;
    }

    .close-modal { position: absolute; left: 20px; top: 20px; color: #888; cursor: pointer; font-size: 1.5rem; }

    /* Result Modal Colors */
    .success-text { color: #00ff66; }
    .error-text { color: #ff4444; }
</style>
@endsection

@section('content')
<div class="orders-page">
    <div class="container">
        <h2 style="color: #fff; margin-bottom: 40px; text-align: right; font-family: 'Reem Kufi', sans-serif;">سجل طلباتك الملكية</h2>

        @forelse($orders as $order)
        <div class="order-card">
            <div class="order-header">
                <div>
                    <span class="order-id">طلب رقم #{{ $order->order_number }}</span>
                    <p style="color: #666; font-size: 0.8rem; margin-top: 5px;">تاريخ الطلب: {{ $order->created_at->format('Y-m-d') }}</p>
                </div>
                <div style="display: flex; gap: 8px; flex-wrap: wrap; justify-content: flex-end;">
                    <span class="status-badge {{ $order->status == 'pending' ? 'status-pending' : ($order->status == 'confirmed' ? 'status-confirmed' : 'status-completed')  }}">
                        <i class="fas fa-truck"></i> {{ $order->status == 'pending' ? 'قيد التجهيز' :  ($order->status == 'confirmed' ? 'جاري التنفيذ' : 'تم التوصيل') }}
                    </span>
                    <span class="status-badge {{ $order->payment_status == 'unpaid' ? 'status-unpaid' : 'status-paid' }}">
                        <i class="fas fa-money-bill-wave"></i> {{ $order->payment_status == 'unpaid' ? 'بانتظار الدفع' : 'تم السداد' }}
                    </span>
                </div>
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
                    <div class="item-subtotal">{{ $item->price * $item->quantity }} ج.م</div>
                </div>
                @endforeach
            </div>

            <div class="shipping-info">
                <i class="fas fa-map-marker-alt"></i> توصيل إلى: {{ $order->user->address }}
            </div>

            <div class="order-billing-details">
                <div class="bill-row grand-total">
                    <span>الإجمالي النهائي</span>
                    <span>{{ $order->total }} ج.م</span>
                </div>
            </div>

            <div class="order-footer">
                @if($order->payment_status == 'unpaid')
                    <button class="pay-now-btn" onclick="openPaymentModal({{ $order->id }}, '{{ $order->total }}')">
                        <i class="fas fa-credit-card"></i> إتمام الدفع الآن
                    </button>
                @else
                    <span style="color: #00ff66; font-weight: bold;"><i class="fas fa-check-circle"></i> تم الدفع</span>
                @endif
            </div>
        </div>
        <div id="paymentModal" class="custom-modal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeModal('paymentModal')">&times;</span>
        <div class="modal-header">اختر وسيلة الدفع</div>
        <p style="margin-bottom: 20px; color: #ccc;">المبلغ المطلوب سداده: <span id="modal_amount" style="color:var(--gold)"></span> ج.م</p>

        <form id="paymentForm" method="POST" action="{{ route('order.payment' , $order->id ) }}" >
            @csrf
            <label class="payment-option">
                <input type="radio" name="gateway" value="paymob" checked>
                <div class="option-box">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT12M4oFIam1Fs9Ys1Q4GIVtJWPTf4PSHxYdg&s" alt="Paymob">
                    <span>دفع عبر Paymob (فيزا / محفظة)</span>
                </div>
            </label>

            <label class="payment-option">
                <input type="radio" name="gateway" value="stripe">
                <div class="option-box">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/b/ba/Stripe_Logo%2C_revised_2016.svg" alt="Stripe">
                    <span>دفع عبر Stripe (عالمي)</span>
                </div>
            </label>

            <button type="submit" class="confirm-pay-btn">تأكيد الانتقال للدفع</button>
        </form>
    </div>
</div>
        @empty
            <div style="text-align: center; color: #888; padding: 100px 20px;">
                <h3>لا توجد طلبات سابقة</h3>
            </div>
        @endforelse
    </div>
</div>


<div id="resultModal" class="custom-modal">
    <div class="modal-content" style="text-align: center;">
        <span class="close-modal" onclick="closeModal('resultModal')">&times;</span>
        <div id="resultIcon" style="font-size: 4rem; margin-bottom: 15px;"></div>
        <div id="resultTitle" style="font-size: 1.5rem; font-weight: bold; margin-bottom: 10px;"></div>
        <p id="resultMessage" style="color: #bbb; margin-bottom: 25px;"></p>
        <button class="confirm-pay-btn" onclick="closeModal('resultModal')">إغلاق</button>
    </div>
</div>

@endsection

@section('js')
<script>
    // فتح مودال الدفع
    function openPaymentModal(orderId, amount) {
        const form = document.getElementById('paymentForm');
        // form.action = `/order/payment/${orderId}`; // روت الدفع بتاعك
        document.getElementById('modal_amount').innerText = amount;
        document.getElementById('paymentModal').style.display = 'flex';
    }

    // إغلاق أي مودال
    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }

    // التعامل مع نتائج السيشن
    window.onload = function() {
        @if(session('success') || session('error'))
            const isSuccess = @json(session('success'));
            const message = isSuccess ? @json(session('success')) : @json(session('error'));

            document.getElementById('resultIcon').innerHTML = isSuccess ? '✅' : '❌';
            document.getElementById('resultIcon').className = isSuccess ? 'success-text' : 'error-text';
            document.getElementById('resultTitle').innerText = isSuccess ? 'عملية ناجحة' : 'فشل الإجراء';
            document.getElementById('resultTitle').className = isSuccess ? 'success-text' : 'error-text';
            document.getElementById('resultMessage').innerText = message;

            document.getElementById('resultModal').style.display = 'flex';
        @endif
    };

    // إغلاق المودال عند الضغط خارج المحتوى
    window.onclick = function(event) {
        if (event.target.className === 'custom-modal') {
            event.target.style.display = 'none';
        }
    }
</script>
@endsection
