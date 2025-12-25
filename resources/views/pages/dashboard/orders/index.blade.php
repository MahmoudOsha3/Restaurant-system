@extends('layout.dashboard.app')

@section('title', 'مراقبة الطلبات المباشرة')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/dashboard/orders.css') }}">
    <style>
        :root { 
            --primary: #e67e22; 
            --secondary: #2c3e50; 
            --success: #27ae60; 
            --danger: #e74c3c; 
            --warning: #f1c40f; 
            --info: #3498db; 
            --bg: #f8f9fa; 
        }

        /* تنسيق كروت الإحصائيات */
        .stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 20px; border-radius: 15px; display: flex; align-items: center; gap: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); border-right: 5px solid transparent; }
        .stat-card i { font-size: 2rem; opacity: 0.8; }
        .stat-card div h3 { margin: 0; font-size: 1.5rem; color: var(--secondary); }
        .stat-card div p { margin: 5px 0 0; color: #888; font-size: 0.9rem; }

        /* شريط الفلتر */
        .filter-bar { background: white; padding: 15px 20px; border-radius: 12px; display: flex; gap: 20px; align-items: flex-end; margin-bottom: 25px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .filter-group { display: flex; flex-direction: column; gap: 5px; }
        .filter-group label { font-size: 0.85rem; color: #666; font-weight: 600; }
        .filter-group input { padding: 8px 12px; border: 1px solid #ddd; border-radius: 8px; outline: none; }
        .btn-filter { background: var(--primary); color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: bold; }

        /* الجدول */
        .orders-table { width: 100%; border-collapse: collapse; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
        .orders-table th, .orders-table td { padding: 15px; text-align: right; border-bottom: 1px solid #eee; }
        .orders-table thead { background: #fcfcfc; }

        /* الحالات */
        .status-pill { padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: bold; display: inline-block; }
        .status-pending { background: #fff4e5; color: #b45d00; } 
        .status-preparing { background: #e1f5fe; color: #01579b; } 
        .status-delivered { background: #e8f5e9; color: #1b5e20; } 

        /* الأزرار */
        .action-btns { display: flex; gap: 8px; }
        .btn-round { width: 35px; height: 35px; border-radius: 50%; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; color: white; transition: 0.3s; }
        .btn-round:hover { transform: scale(1.1); }

        /* النوافذ المنبثقة */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); align-items: center; justify-content: center; z-index: 1000; padding: 20px; }
        .modal-box { background: white; padding: 25px; border-radius: 15px; width: 100%; max-width: 500px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        
        /* الترقيم الصفحي */
        .pagination-container { display: flex; justify-content: space-between; align-items: center; margin-top: 20px; background: white; padding: 15px; border-radius: 12px; }
        .page-link { padding: 8px 15px; border: 1px solid #eee; background: white; cursor: pointer; border-radius: 6px; margin: 0 2px; }
        .page-link.active { background: var(--primary); color: white; border-color: var(--primary); }
    </style>
@endsection

@section('content')
<main class="main-content">
    
    <div class="filter-bar">
        <div class="filter-group">
            <label><i class="fas fa-calendar-alt"></i> من تاريخ</label>
            <input type="date" id="fromDate">
        </div>
        <div class="filter-group">
            <label><i class="fas fa-calendar-alt"></i> إلى تاريخ</label>
            <input type="date" id="toDate">
        </div>
        <button class="btn-filter" onclick="fetchOrders(1)">تطبيق الفلتر</button>
    </div>

    <div class="stats-row">
        <div class="stat-card" style="border-right-color: var(--primary)">
            <i class="fas fa-money-bill-wave" style="color: var(--primary)"></i>
            <div><h3 id="statRevenue">0 ج.م</h3><p>المبيعات</p></div>
        </div>
        <div class="stat-card" style="border-right-color: var(--warning)">
            <i class="fas fa-utensils" style="color: var(--warning)"></i>
            <div><h3 id="statPreparing">0</h3><p>قيد التحضير</p></div>
        </div>
        <div class="stat-card" style="border-right-color: var(--success)">
            <i class="fas fa-check-double" style="color: var(--success)"></i>
            <div><h3 id="statDelivered">0</h3><p>تم التوصيل</p></div>
        </div>
    </div>

    <table class="orders-table">
        <thead>
            <tr>
                <th>رقم الطلب</th>
                <th>الكاشير / الفرع</th>
                <th>محتوى الطلب</th>
                <th>الإجمالي</th>
                <th>الحالة</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody id="ordersBody">
            </tbody>
    </table>

    <div class="pagination-container">
        <div id="paginationInfo" style="color:#666"></div>
        <div id="paginationLinks"></div>
    </div>
</main>

<div id="viewModal" class="modal-overlay">
    <div class="modal-box">
        <h3 style="margin-top:0">تفاصيل الطلب <span id="mvNumber" style="color:var(--primary)"></span></h3>
        <div id="mvContent" style="margin:20px 0; max-height:300px; overflow-y:auto"></div>
        <div style="border-top:2px solid #f8f9fa; padding-top:15px; font-weight:bold; display:flex; justify-content:space-between">
            <span>الإجمالي النهائي:</span>
            <span id="mvTotal" style="font-size:1.2rem; color:var(--success)"></span>
        </div>
        <button class="btn-filter" style="width:100%; margin-top:20px; background:#eee; color:#333" onclick="closeModals()">إغلاق</button>
    </div>
</div>

<div id="statusModal" class="modal-overlay">
    <div class="modal-box" style="max-width:350px; text-align:center">
        <h3>تحديث الحالة</h3>
        <p>تغيير حالة الطلب رقم <b id="msNumber"></b></p>
        <input type="hidden" id="msId">
        <div style="display:grid; gap:10px; margin-top:20px">
            <button class="btn-filter" style="background:var(--warning)" onclick="updateStatus('pending')">قيد الانتظار</button>
            <button class="btn-filter" style="background:var(--info)" onclick="updateStatus('preparing')">يتم التحضير</button>
            <button class="btn-filter" style="background:var(--success)" onclick="updateStatus('delivered')">تم التوصيل</button>
        </div>
        <button onclick="closeModals()" style="background:none; border:none; margin-top:15px; cursor:pointer; color:#999">إلغاء</button>
    </div>
</div>
@endsection

@section('js')
<script>
    let ordersGlobal = [];
    let currentP = 1;

    $(document).ready(function() {
        const today = new Date().toISOString().split('T')[0];
        $('#fromDate, #toDate').val(today);
        fetchOrders(1);
    });

    function fetchOrders(page) {
        currentP = page;
        $.ajax({
            url: `/api/orders?page=${page}`,
            method: 'GET',
            data: { 
                from_date: $('#fromDate').val(), 
                to_date: $('#toDate').val() 
            },
            success: function(res) {
                ordersGlobal = res.data.data;
                renderTable(ordersGlobal);
                renderPagination(res.data);
                updateStats(ordersGlobal);
                $('#lastUpdate').text(new Date().toLocaleTimeString('ar-EG'));
            }
        });
    }

    function renderTable(orders) {
        const tbody = $('#ordersBody').empty();
        orders.forEach(o => {
            let items = o.order_items.map(i => `• ${i.meal_title} (x${i.quantity})`).join('<br>');
            tbody.append(`
                <tr>
                    <td><b>#${o.order_number}</b><br><small style="color:#999">${o.created_at}</small></td>
                    <td><b>${o.admin ? o.admin.name : 'نظام'}</b><br><small>فرع السلام</small></td>
                    <td style="font-size:0.85rem">${items}</td>
                    <td style="font-weight:bold; color:var(--primary)">${o.total} ج.م</td>
                    <td><span class="status-pill ${getStatusClass(o.status)}">${getStatusName(o.status)}</span></td>
                    <td>
                        <div class="action-btns">
                            <button class="btn-round" style="background:var(--info)" onclick="openView(${o.id})"><i class="fas fa-eye"></i></button>
                            <button class="btn-round" style="background:var(--success)" onclick="openStatus(${o.id}, '${o.order_number}')"><i class="fas fa-sync-alt"></i></button>
                        </div>
                    </td>
                </tr>
            `);
        });
    }

    function updateStats(orders) {
        const revenue = orders.filter(o => o.status === 'delivered').reduce((a, b) => a + parseFloat(b.total), 0);
        $('#statRevenue').text(revenue.toLocaleString() + ' ج.م');
        $('#statPreparing').text(orders.filter(o => o.status === 'preparing').length);
        $('#statDelivered').text(orders.filter(o => o.status === 'delivered').length);
    }

    function openView(id) {
        const o = ordersGlobal.find(x => x.id === id);
        $('#mvNumber').text('#' + o.order_number);
        $('#mvTotal').text(o.total + ' ج.م');
        $('#mvContent').html(o.order_items.map(i => `
            <div style="display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px solid #eee">
                <span>${i.meal_title} x ${i.quantity}</span>
                <span>${i.price} ج.م</span>
            </div>
        `).join(''));
        $('#viewModal').css('display', 'flex');
    }

    function openStatus(id, num) {
        $('#msId').val(id);
        $('#msNumber').text(num);
        $('#statusModal').css('display', 'flex');
    }

    function updateStatus(status) {
        const id = $('#msId').val();
        $.ajax({
            url: `/api/orders/update-status/${id}`,
            method: 'POST',
            data: { status: status, _token: '{{ csrf_token() }}' },
            success: function() {
                closeModals();
                fetchOrders(currentP);
            }
        });
    }

    function renderPagination(meta) {
        const links = $('#paginationLinks').empty();
        $('#paginationInfo').text(`إجمالي الطلبات: ${meta.total}`);
        
        links.append(`<button class="page-link" ${meta.current_page === 1 ? 'disabled' : ''} onclick="fetchOrders(${meta.current_page - 1})">السابق</button>`);
        
        for (let i = 1; i <= meta.last_page; i++) {
            if (i === 1 || i === meta.last_page || (i >= meta.current_page - 1 && i <= meta.current_page + 1)) {
                links.append(`<button class="page-link ${i === meta.current_page ? 'active' : ''}" onclick="fetchOrders(${i})">${i}</button>`);
            }
        }
        
        links.append(`<button class="page-link" ${meta.current_page === meta.last_page ? 'disabled' : ''} onclick="fetchOrders(${meta.current_page + 1})">التالي</button>`);
    }

    function closeModals() { $('.modal-overlay').hide(); }
    function getStatusClass(s) { return s === 'pending' ? 'status-pending' : s === 'preparing' ? 'status-preparing' : 'status-delivered'; }
    function getStatusName(s) { return s === 'pending' ? 'انتظار' : s === 'preparing' ? 'تحضير' : 'تم التوصيل'; }
</script>
@endsection