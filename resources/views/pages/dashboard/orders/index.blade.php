@extends('layout.dashboard.app')

@section('title', 'مراقبة الطلبات المباشرة')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/dashboard/orders.css') }}">
    <style>
        :root {
            --primary: #e67e22; --secondary: #2c3e50; --success: #27ae60;
            --danger: #e74c3c; --warning: #f1c40f; --info: #3498db; --white: #ffffff;
        }

        /* إصلاح الـ Modal ليكون مخفياً تماماً وجميلاً */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .modal-box {
            background: var(--white);
            padding: 25px;
            border-radius: 15px;
            width: 95%;
            max-width: 500px;
            box-shadow: 0 20px 25px rgba(0,0,0,0.2);
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }

        /* تنسيق الجدول */
        .orders-table { width: 100%; border-collapse: collapse; background: white; margin-top: 10px; }
        .orders-table th { background: #f8fafc; padding: 15px; border-bottom: 2px solid #edf2f7; text-align: right; }
        .orders-table td { padding: 15px; border-bottom: 1px solid #edf2f7; }

        /* حالات الطلب (Pills) */
        .status-pill { padding: 5px 12px; border-radius: 50px; font-size: 12px; font-weight: 600; display: inline-block; }
        .st-pending { background: #fff4e5; color: #b45d00; }
        .st-confirmed { background: #e0f2fe; color: #0369a1; }
        .st-preparing { background: #fae8ff; color: #a21caf; }
        .st-ready { background: #f0fdf4; color: #166534; }
        .st-delivered { background: #ecfdf5; color: #065f46; border: 1px solid #059669; }
        .st-cancelled { background: #fef2f2; color: #991b1b; }
        .st-completed { background: #f0fdf4; color: #166534; font-weight: 800; }

        /* Pagination احترافي */
        .pagination-container {
            display: flex; justify-content: space-between; align-items: center;
            padding: 20px; background: #fff; border-top: 1px solid #eee; border-radius: 0 0 12px 12px;
        }
        .pagination-links { display: flex; gap: 5px; }
        .page-btn {
            padding: 8px 14px; border: 1px solid #e2e8f0; background: #fff;
            border-radius: 8px; cursor: pointer; transition: 0.2s;
        }
        .page-btn.active { background: var(--primary); color: white; border-color: var(--primary); }
        .page-btn:hover:not(.active) { background: #f8fafc; }

        .btn-round { width: 35px; height: 35px; border-radius: 50%; border: none; color: white; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; }
    </style>
@endsection

@section('content')
<main class="main-content">
    <div class="filter-bar" style="background:white; padding:15px; border-radius:12px; display:flex; gap:15px; margin-bottom:20px; box-shadow:0 2px 4px rgba(0,0,0,0.05)">
        <input type="date" id="fromDate" class="form-control">
        <input type="date" id="toDate" class="form-control">
        <button class="btn-filter" style="background:var(--primary); color:white; border:none; padding:10px 20px; border-radius:8px; cursor:pointer" onclick="fetchOrders(1)">تطبيق الفلتر</button>
    </div>

    <div style="background:white; border-radius:12px; overflow:hidden; box-shadow:0 4px 6px rgba(0,0,0,0.05)">
        <table class="orders-table">
            <thead>
                <tr>
                    <th>رقم الطلب</th>
                    <th>العميل</th>
                    <th>الإجمالي</th>
                    <th>حالة الدفع</th>
                    <th>حالة الطلب</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody id="ordersBody"></tbody>
        </table>

        <div class="pagination-container">
            <div id="paginationInfo" style="color:#64748b; font-size:14px"></div>
            <div class="pagination-links" id="paginationLinks"></div>
        </div>
    </div>
</main>

<div id="viewModal" class="modal-overlay">
    <div class="modal-box">
        <h3 style="margin-top:0; border-bottom:1px solid #eee; padding-bottom:10px">تفاصيل الطلب <span id="mvNumber" style="color:var(--primary)"></span></h3>
        <div id="mvContent" style="margin:20px 0; max-height:250px; overflow-y:auto"></div>

        <div style="background:#f8fafc; padding:15px; border-radius:10px; border:1px solid #edf2f7">
            <div style="display:flex; justify-content:space-between; margin-bottom:5px">
                <span>المجموع الفرعي:</span> <span id="mvSubTotal"></span>
            </div>
            <div style="display:flex; justify-content:space-between; margin-bottom:5px; color:var(--danger)">
                <span>الضريبة (Tax):</span> <span id="mvTax"></span>
            </div>
            <div style="display:flex; justify-content:space-between; font-weight:bold; border-top:1px solid #cbd5e0; padding-top:10px; font-size:1.1rem">
                <span>الإجمالي النهائي:</span> <span id="mvTotal" style="color:var(--success)"></span>
            </div>
        </div>
        <button onclick="closeModals()" style="width:100%; margin-top:20px; padding:12px; background:#e2e8f0; border:none; border-radius:8px; cursor:pointer">إغلاق</button>
    </div>
</div>

<div id="statusModal" class="modal-overlay">
    <div class="modal-box" style="max-width:400px; text-align:center">
        <h3>تحديث حالة التوصيل</h3>
        <p>طلب رقم <b id="msNumber"></b></p>
        <input type="hidden" id="msId">

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px; margin-top:20px">
            <button class="page-btn" style="background:#fef3c7; color:#92400e" onclick="updateStatus('pending')">Pending</button>
            <button class="page-btn" style="background:#dbeafe; color:#1e40af" onclick="updateStatus('confirmed')">Confirmed</button>
            <button class="page-btn" style="background:#f3e8ff; color:#6b21a8" onclick="updateStatus('preparing')">Preparing</button>
            <button class="page-btn" style="background:#fef9c3; color:#854d0e" onclick="updateStatus('ready')">Ready</button>
            <button class="page-btn" style="background:#d1fae5; color:#065f46" onclick="updateStatus('delivered')">Delivered</button>
            <button class="page-btn" style="background:#ecfdf5; color:#064e3b" onclick="updateStatus('completed')">Completed</button>
            <button class="page-btn" style="background:#fee2e2; color:#991b1b; grid-column: span 2" onclick="updateStatus('cancelled')">Cancelled</button>
        </div>
        <button onclick="closeModals()" style="margin-top:15px; background:none; border:none; color:#94a3b8; cursor:pointer">إلغاء</button>
    </div>
</div>
@endsection

@section('js')
<script>
    let ordersGlobal = [];
    let currentP = 1;
    let token = localStorage.getItem('admin_token');

    $(document).ready(function() {
        const today = new Date().toISOString().split('T')[0];
        $('#fromDate, #toDate').val(today);
        fetchOrders(1);

        // إغلاق المودال عند الضغط خارجه
        $(window).on('click', function(e) {
            if ($(e.target).hasClass('modal-overlay')) closeModals();
        });
    });

    function fetchOrders(page) {
        currentP = page;
        $.ajax({
            url: `/api/orders?page=${page}`,
            method: 'GET',
            headers: { 'Authorization': 'Bearer ' + token },
            data: { from_date: $('#fromDate').val(), to_date: $('#toDate').val() },
            success: function(res) {
                ordersGlobal = res.data.data;
                renderTable(ordersGlobal);
                renderPagination(res.data);
            }
        });
    }

    function renderTable(orders) {
        const tbody = $('#ordersBody').empty();
        orders.forEach(order => {
            tbody.append(`
                <tr>
                    <td><b>#${order.order_number}</b></td>
                    <td>${order.user ? order.user.name : 'كاشير'}</td>
                    <td style="font-weight:bold">${order.total} ج.م</td>
                    <td style="color:${order.payment_status === 'paid' ? '#27ae60' : '#e74c3c'}">
                        <b>${order.payment_status === 'paid' ? 'مدفوع' : 'غير مدفوع'}</b>
                    </td>
                    <td><span class="status-pill st-${order.status}">${order.status}</span></td>
                    <td>
                        <div style="display:flex; gap:8px">
                            <button class="btn-round" style="background:var(--info)" onclick="openView(${order.id})"><i class="fas fa-eye"></i></button>
                            <button class="btn-round" style="background:var(--success)" onclick="openStatus(${order.id}, '${order.order_number}, ${order.status}')"><i class="fas fa-sync-alt"></i></button>
                        </div>
                    </td>
                </tr>
            `);
        });
    }

    function renderPagination(meta) {
        const links = $('#paginationLinks').empty();
        $('#paginationInfo').text(`عرض ${meta.from} - ${meta.to} من ${meta.total} طلب`);

        links.append(`<button class="page-btn" ${meta.current_page === 1 ? 'disabled' : ''} onclick="fetchOrders(${meta.current_page - 1})">السابق</button>`);

        for (let i = 1; i <= meta.last_page; i++) {
            if (i === 1 || i === meta.last_page || (i >= meta.current_page - 1 && i <= meta.current_page + 1)) {
                links.append(`<button class="page-btn ${i === meta.current_page ? 'active' : ''}" onclick="fetchOrders(${i})">${i}</button>`);
            }
        }

        links.append(`<button class="page-btn" ${meta.current_page === meta.last_page ? 'disabled' : ''} onclick="fetchOrders(${meta.current_page + 1})">التالي</button>`);
    }

    function openView(id) {
        const o = ordersGlobal.find(x => x.id === id);
        $('#mvNumber').text('#' + o.order_number);

        let tax = parseFloat(o.tax || 0);
        let total = parseFloat(o.total);
        let subtotal = total - tax;

        $('#mvSubTotal').text(subtotal.toFixed(2) + ' ج.م');
        $('#mvTax').text(tax.toFixed(2) + ' ج.م');
        $('#mvTotal').text(total.toFixed(2) + ' ج.م');

        $('#mvContent').html(o.order_items.map(i => `
            <div style="display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid #f1f5f9">
                <span>${i.meal_title} x ${i.quantity}</span>
                <span>${(i.price * i.quantity)} ج.م</span>
            </div>
        `).join(''));
        $('#viewModal').css('display', 'flex');
    }

    function openStatus(id, num , status ) {
        $('#msId').val(id);
        $('#msNumber').text(num);
        $('#statusOrder').text(status) ;
        $('#statusModal').css('display', 'flex');
    }

    function updateStatus(status) {
        const id = $('#msId').val();
        $.ajax({
            url: `/api/orders/${id}`,
            method: 'PUT',
            headers : {
                'Accept' : 'application/json' ,
                'Authorization': 'Bearer ' + token,
            } ,
            data: { status: status },
            success: function() {
                closeModals();
                fetchOrders(currentP);
                toastr.success('تم تحديث الطلب بنجاح ') ;
            },
            error: function(xhr){
                closeModals();
                toastr.error(xhr.responseJSON?.message) ;
            }
        });
    }

    function closeModals() { $('.modal-overlay').hide(); }
</script>
@endsection
