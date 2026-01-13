@extends('layout.dashboard.app')

@section('title' , 'الكاشير | الشيخ المندي')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/dashboard/cashier.css') }}">
<style>
    .modal-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.6); display: flex; justify-content: center;
        align-items: center; z-index: 1000; backdrop-filter: blur(3px);
    }
    .modal-overlay.hidden { display: none; }
    .modal-content {
        background: white; padding: 30px; border-radius: 15px;
        text-align: center; width: 400px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }
    .confirm-btns { display: flex; gap: 15px; margin-top: 25px; }
    .btn-confirm { flex: 1; padding: 12px; background: var(--success); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; }
    .btn-cancel { flex: 1; padding: 12px; background: #e74c3c; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; }

    /* تنسيق زر الحذف الفردي */
    .remove-item { color: #e74c3c; cursor: pointer; padding: 5px; transition: 0.2s; }
    .remove-item:hover { transform: scale(1.2); }

    /* تنسيق زر حذف الكل */
    .clear-all-btn { color: #e74c3c; background: none; border: 1px solid #e74c3c; padding: 4px 10px; border-radius: 5px; cursor: pointer; font-size: 0.8rem; transition: 0.3s; }
    .clear-all-btn:hover { background: #e74c3c; color: white; }
</style>
@endsection

@section('content')
<div id="app">
    <div class="main-container">
        <header>
            <h3>شاشة الكاشير</h3>
            <div style="font-weight: bold; color: var(--dark);">
                {{ auth()->user()->name }} <i class="fas fa-user-shield" style="margin-right: 5px; color: var(--primary);"></i>
            </div>
        </header>

        <div class="pos-layout">
            <div class="meals-area">
                <input type="text" id="mealSearch" placeholder="🔍 ابحث عن وجبة..." style="width:100%; padding:12px; border-radius:8px; border:1px solid #ddd; margin-bottom:20px">
                <div class="meals-grid">
                @foreach ($meals as $meal)
                    <div class="meal-card" onclick="addToCart({{$meal->id}})" style="cursor: pointer">
                        <i class="fas fa-hamburger" style="color: #f39c12; font-size: 1.5rem;"></i>
                        <h5 style="font-size: 0.8rem; margin: 8px 0;">{{$meal->title}}</h5>
                        <b style="color: var(--primary); font-size: 0.85rem;">{{$meal->price}} ج.م</b>
                    </div>
                @endforeach
                </div>
            </div>

            <div class="cart-area">
                <div style="padding:15px 20px; font-weight:bold; background:#f9f9f9; border-bottom:1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
                    <span>الطلب الحالي</span>
                </div>

                <div style="flex:1; overflow-y:auto; padding:15px" id="cartItems">
                    {{-- محتوى السلة عبر JS --}}
                </div>

                <div style="padding:20px; border-top:2px solid #eee; background: #fff;">
                    <div style="font-size: 0.9rem; color: #777; margin-bottom: 5px; display:flex; justify-content:space-between;">
                        <span>المجموع الفرعي:</span>
                        <span id="subtotalPrice">0 ج.م</span>
                    </div>
                    <div style="font-size: 0.9rem; color: #777; margin-bottom: 10px; display:flex; justify-content:space-between;">
                        <span>الضريبة ({{ config('order.tax') * 100 }}%):</span>
                        <span id="taxAmount">0 ج.م</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; font-size:1.3rem; font-weight:bold; margin-bottom:15px; border-top: 1px dashed #ddd; pt-10">
                        <span>الإجمالي</span>
                        <span id="totalPrice">0 ج.م</span>
                    </div>
                    <button onclick="openPaymentModal()" style="width:100%; padding:15px; background:var(--success); color:white; border:none; border-radius:10px; cursor:pointer; font-weight:bold; font-size: 1.1rem;">
                        إتمام الطلب
                    </button>
                </div>
            </div>
        </div>

        <div id="paymentModal" class="modal-overlay hidden">
            <div class="modal-content">
                <i class="fas fa-check-circle fa-3x" style="color: var(--success); margin-bottom: 15px;"></i>
                <h3 style="margin-bottom: 5px;">تأكيد الطلب</h3>
                <h2 id="modalTotalPrice" style="color: var(--primary); margin-bottom: 20px;">0 ج.م</h2>
                <div class="confirm-btns">
                    <button class="btn-confirm" onclick="submitOrder()">تأكيد</button>
                    <button class="btn-cancel" onclick="closePaymentModal()">إلغاء</button>
                </div>
            </div>
        </div>
</div>
@endsection

@section('js')
<script>
    let carts = [];
    let currentTotal = 0;
    const tax = {{ config('order.tax') }};

    $(document).ready(function(){
        fetchCarts();
        $('#mealSearch').on('keyup', function() {
            let value = $(this).val().toLowerCase();
            $(".meal-card").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });

    function fetchCarts(){
        $.ajax({
            url: `{{ url('cashier/carts') }}`,
            method: "GET",
            success: function(res){
                carts = res.data;
                updateUI(carts);
            },
            error: function(xhr){ console.error(xhr.responseText); }
        });
    }

    function addToCart(mealId) {
        $.ajax({
            url: `{{ url('cashier/carts') }}`,
            method: 'POST',
            data: { meal_id: mealId, quantity: 1, admin_id: {{ auth()->user()->id }} },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (res){
                fetchCarts();
                toastr.success('تم الإضافة');
            },
            error: function(xhr){ toastr.error('خطأ في الإضافة'); }
        });
    }

    // وظيفة حذف عنصر واحد
    function removeFromCart(cartId) {
        if(!confirm('حذف الوجبة من السلة؟')) return;
        $.ajax({
            url: `{{ url('cashier/carts') }}/${cartId}`,
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res) {
                fetchCarts();
                toastr.info('تم الحذف');
            }
        });
    }

    function updateUI(carts) {
        const itemsContainer = document.getElementById('cartItems');
        let htmlContent = '';
        let subtotal = 0;

        if (!carts || carts.length === 0) {
            htmlContent = `<div style="text-align:center; color:#ccc; margin-top:50px">السلة فارغة</div>`;
        } else {
            carts.forEach(cart => {
                let itemTotal = cart.meal.price * cart.quantity;
                subtotal += itemTotal;
                htmlContent += `
                    <div style="display:flex; justify-content:space-between; margin-bottom:12px; border-bottom: 1px solid #f5f5f5; padding-bottom: 8px; align-items: center;">
                        <div style="flex: 1;">
                            <span style="display:block; font-weight: 600; font-size: 0.9rem;">${cart.meal.title}</span>
                            <small>${cart.meal.price} × ${cart.quantity}</small>
                        </div>
                        <div style="text-align: left;">
                            <span style="font-weight: bold; display: block;">${itemTotal} ج.م</span>
                            <i class="fas fa-trash-alt remove-item" onclick="removeFromCart(${cart.id})"></i>
                        </div>
                    </div>`;
            });
        }

        itemsContainer.innerHTML = htmlContent;
        let tax_finished = subtotal * tax ;
        let total = subtotal + tax_finished;
        currentTotal = total;

        document.getElementById('subtotalPrice').innerText = subtotal.toFixed(2) + ' ج.م';
        document.getElementById('taxAmount').innerText = tax_finished.toFixed(2) + ' ج.م';
        document.getElementById('totalPrice').innerText = total.toFixed(2) + ' ج.م';
    }

    function openPaymentModal() {
        if (carts.length === 0) return toastr.warning('السلة فارغة');
        document.getElementById('modalTotalPrice').innerText = currentTotal.toFixed(2) + ' ج.م';
        document.getElementById('paymentModal').classList.remove('hidden');
    }

    function closePaymentModal() { document.getElementById('paymentModal').classList.add('hidden'); }

    function submitOrder() {
        $(".btn-confirm").prop('disabled', true).text('جاري المعالجة...');
        $.ajax({
            url: `{{ url('cashier/order') }}`,
            method: 'POST',
            data: {
                status: 'completed',
                admin_id: {{ auth()->user()->id }}
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (res) {
                toastr.success('تم الطلب بنجاح');
                closePaymentModal();
                fetchCarts();
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                toastr.error('فشل إنشاء الطلب، راجع الـ Console');
            },
            complete: function() {
                $(".btn-confirm").prop('disabled', false).text('تأكيد');
            }
        });
    }
</script>
@endsection
