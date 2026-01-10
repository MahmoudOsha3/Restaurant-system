@extends('layout.site.app')

@section('title', 'إتمام الطلب - شيخ المندي')

@section('css')
<style>
    .checkout-container { padding: 120px 20px 60px; background: #0a0a0a; min-height: 100vh; }
    .checkout-grid { max-width: 1100px; margin: 0 auto; display: grid; grid-template-columns: 1.5fr 1fr; gap: 30px; }

    .checkout-card { background: #151515; border-radius: 20px; border: 1px solid #333; padding: 30px; }
    .checkout-card h3 { color: var(--gold); margin-bottom: 25px; display: flex; align-items: center; gap: 10px; }

    /* تفاصيل الشحن */
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
    .form-group { text-align: right; }
    .form-group label { display: block; color: #888; margin-bottom: 8px; font-size: 0.9rem; }
    .form-group input, .form-group textarea {
        width: 100%; padding: 12px; background: #0a0a0a; border: 1px solid #333; border-radius: 10px; color: #fff;
    }

    /* ملخص الطلب */
    .order-summary-item { display: flex; justify-content: space-between; padding: 15px 0; border-bottom: 1px solid #222; color: #bbb; }
    .total-row { display: flex; justify-content: space-between; padding: 20px 0; color: var(--gold); font-size: 1.4rem; font-weight: bold; }

    .confirm-order-btn {
        width: 100%; background: var(--gold); color: #000; padding: 18px; border: none; border-radius: 12px;
        font-weight: bold; font-size: 1.2rem; cursor: pointer; transition: 0.3s; margin-top: 20px;
    }
    .confirm-order-btn:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(193, 155, 118, 0.3); }

    @media (max-width: 900px) { .checkout-grid { grid-template-columns: 1fr; } }
    <style>
    .checkout-items-list {
        max-height: 400px;
        overflow-y: auto;
        margin-bottom: 20px;
        padding-right: 5px;
    }

    /* ستايل الوجبة الواحدة داخل التشيك أوت */
    .checkout-item-row {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 12px 0;
        border-bottom: 1px solid #222;
    }

    .checkout-item-row img {
        width: 70px;
        height: 70px;
        border-radius: 12px;
        object-fit: cover;
        border: 1px solid #333;
    }

    .item-info { flex: 1; }
    .item-info h4 { color: #fff; font-size: 0.95rem; margin-bottom: 4px; }
    .item-info p { color: var(--gold); font-size: 0.85rem; font-weight: bold; }

    .item-qty-badge {
        background: #222;
        color: #fff;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        border: 1px solid #444;
    }

    .summary-details {
        background: #0d0d0d;
        padding: 20px;
        border-radius: 15px;
        margin-top: 15px;
    }

    .total-row-highlight {
        display: flex;
        justify-content: space-between;
        padding-top: 15px;
        margin-top: 10px;
        border-top: 2px dashed #333;
        color: var(--gold);
        font-size: 1.5rem;
        font-weight: 900;
    }

    /* تخصيص السكرول بار */
    .checkout-items-list::-webkit-scrollbar { width: 5px; }
    .checkout-items-list::-webkit-scrollbar-thumb { background: var(--gold); border-radius: 10px; }
</style>
</style>
@endsection

@section('content')
<div class="checkout-container">
    <div class="checkout-grid">

        <div class="checkout-card animate-up">
            <h3><i class="fas fa-truck"></i> تفاصيل الشحن والتوصيل</h3>
            <form id="checkoutForm" action="{{ route('order.store') }}" method="POST">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label>الاسم الكامل</label>
                        <input type="text" name="customer_name" value="{{ auth()->user()->name }}" required>
                    </div>
                    <div class="form-group">
                        <label>رقم الهاتف</label>
                        <input type="tel" name="customer_phone" value="{{ auth()->user()->phone }}" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>المدينة</label>
                        <input type="text" name="city" value="{{ auth()->user()->city }}" required>
                    </div>
                    <div class="form-group">
                        <label>المنطقة / الحي</label>
                        <input type="text" name="area" placeholder="مثلاً: المعادي" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>العنوان بالتفصيل</label>
                    <textarea name="address" rows="3" required>{{ auth()->user()->address }}</textarea>
                </div>

                <input type="hidden" name="cart_data" id="cartDataInput">
            </form>
        </div>
        <div class="checkout-card animate-up" style="height: fit-content;">
            @if ($carts->isEmpty())
                <h1>السلة فارغة</h1>
            @else
            <h3><i class="fas fa-shopping-bag"></i> ملخص طلبك</h3>

            <div id="checkoutItemsList" class="checkout-items-list">
                @foreach ($carts as $cart)
                <div class="checkout-item-row">
                    <img src="{{ $cart->meal->image_url }}" alt="${item.name}">
                    <div class="item-info">
                        <h4>{{ $cart->meal->title }}</h4>
                        <p>{{ $cart->meal->price * $cart->quantity }} ج.م</p>
                    </div>
                    <div class="item-qty-badge">
                        {{ $cart->quantity }} ×
                    </div>
                </div>
                @endforeach
            </div>

            <div class="summary-details">
                <div class="order-summary-item">
                    <span>إجمالي الوجبات</span>
                    <span id="subtotal">{{ $subTotal }} ج.م</span>
                </div>
                <div class="order-summary-item">
                    <span>الضريبه</span>
                    <span class="delivery-fee">{{ config('order.tax') * $subTotal }} ج.م</span>
                </div>
                <div class="order-summary-item">
                    <span>رسوم التوصيل</span>
                    <span class="delivery-fee">{{ config('order.delivery_fee') }} ج.م</span>
                </div>
                <div class="total-row-highlight">
                    <span>الإجمالي النهائي</span>
                    <span id="finalTotal">{{ (config('order.tax') * $subTotal) + $subTotal + config('order.delivery_fee') }} ج.م</span>
                </div>
            </div>
            <form action="{{ route('order.store') }}" method="post">
                @csrf

                <button type="submit" class="confirm-order-btn">
                    تأكيد وطلب الآن <i class="fas fa-paper-plane"></i>
                </button>
            </form>
            @endif
        </div>

    </div>
</div>
@endsection

@section('js')
<script>
    let carts = [] ;

    $(document).ready(function(){
        fetchCarts() ;
    }) ;

    function fetchCarts(){
        $.ajax({
            url :`carts` ,
            method : "GET" ,
            headers : {
                'Accept' : 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success : function(res){
                carts = res.data ;
                updateUI(carts) ;
            },
            error : function(){

            } ,
        }) ;
    }

    function updateUI() {
        const itemsCont = document.getElementById('cartItems');
        const cartTotal = document.getElementById('cartTotal');
        const cartCount = document.getElementById('cart-count');

        let total = 0;
        let count = 0;

        if (carts.length === 0) {
            itemsCont.innerHTML = '<p class="empty-msg">سلتك بانتظار أشهى المأكولات</p>';
        } else {

            itemsCont.innerHTML = '';
            carts.forEach(cart => {
                total += cart.meal.price * cart.quantity;
                count += cart.quantity ;

                itemsCont.innerHTML += `
                    <div class="cart-item">
                        <img src="${cart.meal.image_url}" alt="${cart.meal.title}" class="cart-item-img">
                        <div class="cart-item-info">
                            <h4>${cart.meal.title}</h4>
                            <p>${cart.meal.price} ج.م</p>
                        </div>
                        <div class="qty-controls">
                            <button onclick="changeQty(${cart.id}, -1)">-</button>
                            <span>${cart.quantity}</span>
                            <button onclick="changeQty(${cart.id}, 1)">+</button>
                        </div>
                    </div>
                `;
            });
        }

        // تحديث الأرقام النهائية
        cartTotal.innerText = total;
        cartCount.innerText = count;
    }

    function changeQty(id, amt) {
        const item = carts.find(cart => cart.id === id);
        if (!item) return;

        $.ajax({
            url:`api/cart/${id}`,
            method:'PUT' ,
            headers : {
                'Accept' : 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data : {
                quantity: amt ,
            } ,
            success : function(){
                fetchCarts() ;
            },
            error: function(xhr){
                alert(xhr.responseJSON?.message) ;
            }
        }) ;
        if(item.quantity <= 0) {
            cart = carts.filter(i => i.id !== id);
        }
        updateUI();
    }


</script>
@endsection
