@extends('layout.dashboard.app')

@section('title' , "سجل العمليات - الكاشير")

@section('css')
<style>

        #app { display: flex; width: 100%; }

        /* Sidebar */
        .sidebar { width: 240px; background: var(--dark); color: white; display: flex; flex-direction: column; }
        .sidebar-header { padding: 25px; text-align: center; font-size: 1.5rem; font-weight: bold; color: var(--primary); border-bottom: 1px solid #3e4f5f; }
        .nav-item { padding: 15px 25px; cursor: pointer; transition: 0.3s; display: flex; align-items: center; gap: 12px; }
        .nav-item:hover, .nav-item.active { background: var(--primary); color: white; }

        /* Main Content */
        .main-container { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
        header { height: 70px; background: white; border-bottom: 1px solid #ddd; display: flex; align-items: center; padding: 0 30px; justify-content: space-between; }

    /* تحسينات عامة للمحتوى */
    .orders-wrapper {
        width: 98%;
        margin: 15px auto;
        padding: 5px;
        min-height: 85vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* كروت الإحصائيات */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 25px;
    }
    .stat-card-custom {
        background: #fff;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        gap: 20px;
        border-bottom: 4px solid #e67e22;
        transition: transform 0.3s;
    }
    .stat-card-custom:hover { transform: translateY(-5px); }
    .stat-card-custom i { font-size: 2.5rem; color: #e67e22; opacity: 0.8; }
    .stat-info h3 { margin: 0; font-weight: 800; color: #2c3e50; }
    .stat-info span { font-size: 0.9rem; color: #7f8c8d; }

    /* قسم الجدول والبحث */
    .table-section {
        background: #fff;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    #order-search-input {
        border: 2px solid #f1f1f1;
        border-radius: 10px;
        padding: 12px 20px;
        width: 100%;
        max-width: 400px;
        transition: 0.3s;
    }
    #order-search-input:focus { border-color: #e67e22; outline: none; }

    /* تنسيق الجدول */
    .order-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    .order-table th { padding: 18px; background: #fafafa; color: #34495e; font-weight: 700; text-align: right; border-bottom: 2px solid #eee; }
    .order-table td { padding: 18px; border-bottom: 1px solid #f7f7f7; vertical-align: middle; }
    .order-id-badge { background: #34495e; color: #fff; padding: 6px 12px; border-radius: 8px; font-weight: bold; }

    /* الترقيم (Pagination) على الجنب */
    .pagination-container {
        margin-top: 25px;
        display: flex;
        justify-content: flex-start;
        gap: 8px;
        direction: rtl;
    }

    .page-link-custom {
        padding: 10px 18px;
        border: 1px solid #eee;
        background: #fff;
        color: #333;
        cursor: pointer;
        border-radius: 10px;
        transition: 0.3s;
        font-weight: 600;
        border: none;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .page-link-custom.active {
        background: #e67e22;
        color: #fff;
    }
    .page-link-custom.disabled { opacity: 0.5; cursor: not-allowed; pointer-events: none; }

    /* المودال (الفاتورة) */
    .details-modal {
        display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7);
        z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px);
    }
    .modal-content-custom {
        background: #fff; width: 500px; border-radius: 20px; padding: 30px;
        position: relative; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 50px rgba(0,0,0,0.3);
    }
    .bill-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px dashed #eee; }
    .text-orange { color: #e67e22 !important; }

    /* ستايل جدول الفاتورة المصغر */
    .invoice-table { width: 100%; margin-top: 15px; }
    .invoice-table th { color: #888; font-size: 0.85rem; border-bottom: 2px solid #f4f4f4; padding-bottom: 8px; }
    .invoice-table td { padding: 12px 0; border-bottom: 1px solid #f9f9f9; font-size: 0.95rem; }
</style>
@endsection

@section('content')
<div id="app">
    <div class="main-container">
        <header>
            <h3>{{ auth()->user()->role->name == 'Call center' ? 'سجل العمليات' : 'سجل الطلبات'   }}</h3>
            <div style="font-weight: bold; color: var(--dark);">
                {{ auth()->user()->name }} <i class="fas fa-user-shield" style="margin-right: 5px; color: var(--primary);"></i>
            </div>
        </header>


<div class="orders-wrapper">
    <div class="stats-container">
        <div class="stat-card-custom">
            <i class="fas fa-wallet"></i>
            <div class="stat-info">
                <span>الإجمالي اليومي</span>
                <h3 id="total-amount">0.00 ج.م</h3>
            </div>
        </div>
        <div class="stat-card-custom" style="border-bottom-color: #27ae60;">
            <i class="fas fa-shopping-cart text-success"></i>
            <div class="stat-info">
                <span>عدد الطلبات المنفذة</span>
                <h3 id="orders-count">0</h3>
            </div>
        </div>
    </div>

    <div class="table-section">
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
            <h4 class="fw-bold mb-0">سجل عمليات الكاشير بتاريخ : {{ now() }}</h4><br>
            <input type="text" id="order-search-input" placeholder="🔍 ابحث برقم الطلب (Order ID)...">
        </div>

        <div class="table-responsive">
            <table class="order-table" dir="rtl">
                <thead>
                    <tr>
                        <th>رقم الطلب</th>
                        <th>توقيت العملية</th>
                        <th>المبلغ الإجمالي</th>
                        <th class="text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="orders-list-body">
                    <tr><td colspan="4" class="text-center py-5">جاري جلب السجلات...</td></tr>
                </tbody>
            </table>
        </div>

        <div id="pagination-wrapper" class="pagination-container"></div>
    </div>
</div>
    </div>
</div>

<div id="orderModal" class="details-modal">
    <div class="modal-content-custom text-right" dir="rtl">
        <div class="text-center mb-4">
            <h4 class="fw-bold mb-1">تفاصيل الفاتورة</h4>
            <span id="det-number" class="badge bg-light text-dark p-2 border"></span>
        </div>

        <div id="modal-items-content">
            </div>

        <div class="bg-light p-3 rounded mt-4 border">
            <div class="bill-row">
                <span class="text-muted">المجموع الفرعي:</span>
                <span id="det-subtotal" class="fw-bold"></span>
            </div>
            <div class="bill-row">
                <span class="text-muted">الضريبة (Tax):</span>
                <span id="det-tax" class="fw-bold"></span>
            </div>
            <div class="bill-row border-0 mt-2">
                <strong class="text-dark" style="font-size: 1.1rem;">الإجمالي النهائي:</strong>
                <strong class="text-orange" style="font-size: 1.3rem;" id="det-total"></strong>
            </div>
        </div>

        <button class="btn btn-dark w-100 mt-4 py-2 fw-bold shadow-sm" onclick="$('#orderModal').fadeOut(200)">إغلاق النافذة</button>
    </div>
</div>
@endsection

@section('js')
<script>
    let allOrders = [];

    $(document).ready(function() {
        fetchOrders(1);

        // إغلاق المودال عند الضغط خارجه
        $(window).on('click', function(e) {
            if ($(e.target).hasClass('details-modal')) {
                $('.details-modal').fadeOut(200);
            }
        });

        // البحث اللحظي
        $('#order-search-input').on('input', function() {
            const val = $(this).val();
            if(val) {
                const filtered = allOrders.filter(o => o.order_number.toString().includes(val));
                renderOrders(filtered);
            } else {
                renderOrders(allOrders);
            }
        });
    });

    function fetchOrders(page) {
        $.ajax({
            url: `{{ url('cashier/history/log') }}?page=${page}`,
            method: 'GET',
            success: function(response) {
                // حسب الـ API الخاص بك: response.data يحتوي على stats و orders
                const meta = response.data;
                allOrders = meta.orders.data;

                renderOrders(allOrders);
                updateStats(meta.stats);
                renderPagination(meta.orders); // نمرر كائن الـ orders لأنه يحتوي على معلومات الترقيم
            },
            error: function() {
                $('#orders-list-body').html('<tr><td colspan="4" class="text-center text-danger">فشل في الاتصال بالسيرفر</td></tr>');
            }
        });
    }

    function renderOrders(data) {
        const tbody = $('#orders-list-body');
        tbody.empty();

        if (data.length === 0) {
            tbody.append('<tr><td colspan="4" class="text-center py-4 text-muted">لا توجد نتائج</td></tr>');
            return;
        }

        data.forEach(order => {
            const time = new Date(order.created_at).toLocaleTimeString('ar-EG', {hour:'2-digit', minute:'2-digit'});
            tbody.append(`
                <tr>
                    <td><span class="order-id-badge">#${order.order_number}</span></td>
                    <td><i class="far fa-clock text-muted me-1"></i> ${time}</td>
                    <td class="fw-bold text-dark">${order.total} ج.م</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-primary px-3 shadow-sm" onclick="showOrderDetails('${order.order_number}')">
                            <i class="fas fa-receipt me-1"></i> عرض الفاتورة
                        </button>
                    </td>
                </tr>
            `);
        });
    }

    function renderPagination(meta) {
        const wrapper = $('#pagination-wrapper');
        wrapper.empty();

        // السابق
        const prevDisabled = meta.current_page === 1 ? 'disabled' : '';
        wrapper.append(`<button class="page-link-custom ${prevDisabled}" onclick="fetchOrders(${meta.current_page - 1})">السابق</button>`);

        // الأرقام
        for (let i = 1; i <= meta.last_page; i++) {
            const active = (i === meta.current_page) ? 'active' : '';
            wrapper.append(`<button class="page-link-custom ${active}" onclick="fetchOrders(${i})">${i}</button>`);
        }

        // التالي
        const nextDisabled = meta.current_page === meta.last_page ? 'disabled' : '';
        wrapper.append(`<button class="page-link-custom ${nextDisabled}" onclick="fetchOrders(${meta.current_page + 1})">التالي</button>`);
    }

    function showOrderDetails(orderNumber) {
        const order = allOrders.find(o => o.order_number == orderNumber);
        if (!order) return;

        $('#det-number').text('رقم الطلب: ' + order.order_number);
        $('#det-subtotal').text(order.subtotal + ' ج.م');
        $('#det-tax').text(order.tax + ' ج.م');
        $('#det-total').text(order.total + ' ج.م');

        let itemsHtml = `
            <table class="invoice-table" dir="rtl">
                <thead>
                    <tr>
                        <th class="text-right">الوجبة</th>
                        <th class="text-center">الكمية</th>
                        <th class="text-left">الإجمالي</th>
                    </tr>
                </thead>
                <tbody>
        `;

        order.order_items.forEach(item => {
            itemsHtml += `
                <tr>
                    <td>${item.meal_title}</td>
                    <td class="text-center">x${item.quantity}</td>
                    <td class="text-left fw-bold">${item.total} ج.م</td>
                </tr>
            `;
        });

        itemsHtml += '</tbody></table>';
        $('#modal-items-content').html(itemsHtml);
        $('#orderModal').fadeIn(200).css('display', 'flex');
    }

    function updateStats(stats) {
        if(stats) {
            $('#total-amount').text(parseFloat(stats.total_price).toLocaleString('ar-EG') + ' ج.م');
            $('#orders-count').text(stats.count_orders);
        }
    }
</script>
@endsection
