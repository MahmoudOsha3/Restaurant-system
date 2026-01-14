@extends('layout.dashboard.app')

@section('title', 'إدارة المستخدمين')

@section('css')
    <style>
        :root {
            --primary: #e67e22; --secondary: #2c3e50; --success: #27ae60;
            --info: #3498db; --white: #ffffff; --border: #edf2f7;
            --text-muted: #64748b;
        }

        /* شريط البحث */
        .search-container {
            background: white; padding: 15px; border-radius: 12px;
            margin-bottom: 20px; display: flex; gap: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .search-input {
            flex: 1; padding: 10px 15px; border: 1px solid var(--border);
            border-radius: 8px; outline: none; transition: 0.3s;
        }
        .search-input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(230, 126, 34, 0.1); }

        /* الجدول والمودال */
        .custom-card { background: var(--white); border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); overflow: hidden; }
        .main-table { width: 100%; border-collapse: collapse; }
        .main-table th { background: #f8fafc; padding: 16px; text-align: right; font-size: 13px; color: var(--text-muted); border-bottom: 2px solid var(--border); }
        .main-table td { padding: 16px; border-bottom: 1px solid var(--border); font-size: 14px; }

        .modal-overlay {
            display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6);
            backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center;
        }
        .modal-box { background: var(--white); width: 95%; max-width: 750px; border-radius: 16px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); }

        /* Wizard Tabs */
        .wizard-nav { display: flex; background: #f8fafc; border-bottom: 1px solid var(--border); }
        .nav-link { padding: 15px 25px; cursor: pointer; color: var(--text-muted); font-weight: bold; transition: 0.3s; position: relative; }
        .nav-link.active { color: var(--primary); background: white; }
        .nav-link.active::after { content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 3px; background: var(--primary); }
        .tab-pane { display: none; padding: 25px; }
        .tab-pane.active { display: block; }

        .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; }
        .info-card { background: #f1f5f9; padding: 15px; border-radius: 10px; border: 1px solid #e2e8f0; }
        .info-card label { display: block; font-size: 11px; color: var(--text-muted); margin-bottom: 5px; text-transform: uppercase; letter-spacing: 0.5px; }
        .info-card p { margin: 0; font-weight: 700; color: var(--secondary); }

        /* Pagination الاحترافي */
        .pagination-wrapper { padding: 20px; display: flex; justify-content: space-between; align-items: center; background: #fff; border-top: 1px solid var(--border); }
        .page-btn {
            width: 35px; height: 35px; display: inline-flex; align-items: center; justify-content: center;
            border-radius: 8px; border: 1px solid var(--border); background: white;
            color: var(--secondary); cursor: pointer; transition: 0.3s; font-weight: 600; font-size: 13px;
        }
        .page-btn:hover { border-color: var(--primary); color: var(--primary); background: #fff5ed; }
        .page-btn.active { background: var(--primary); color: white; border-color: var(--primary); box-shadow: 0 4px 10px rgba(230, 126, 34, 0.3); }
        .page-btn:disabled { cursor: not-allowed; opacity: 0.5; background: #f8fafc; }

        .btn-primary { background: var(--primary); color: white; border: none; padding: 10px 25px; border-radius: 8px; cursor: pointer; font-weight: bold; display: inline-flex; align-items: center; gap: 8px; }
        .status-badge { padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; }
        .bg-success-light { background: #dcfce7; color: #166534; }
    </style>
@endsection

@section('content')
<main class="main-content" style="padding: 20px;">
    <div class="search-container">
        <input type="text" id="userSearch" class="search-input" placeholder="بحث برقم الهاتف أو الاسم...">
        <button class="btn-primary" onclick="fetchUsers(1)">
            <i class="fas fa-search"></i> بحث
        </button>
    </div>

    <div class="custom-card">
        <table class="main-table">
            <thead>
                <tr>
                    <th>المستخدم</th>
                    <th>التواصل</th>
                    <th>تاريخ التسجيل</th>
                    <th>العنوان</th>
                    <th style="text-align: center;">العمليات</th>
                </tr>
            </thead>
            <tbody id="usersList"></tbody>
        </table>

        <div class="pagination-wrapper">
            <div id="pageText" style="font-size: 14px; color: var(--text-muted); font-weight: 500;"></div>
            <div id="pageLinks" style="display: flex; gap: 8px; align-items: center;"></div>
        </div>
    </div>
</main>

<div id="userWizard" class="modal-overlay">
    <div class="modal-box">
        <div style="padding: 20px; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin:0; font-size: 18px;">ملف المستخدم: <span id="userNameHeader" style="color:var(--primary)"></span></h3>
            <button onclick="closeWizard()" style="background:none; border:none; font-size: 20px; cursor:pointer; color:#94a3b8">&times;</button>
        </div>

        <div class="wizard-nav">
            <div class="nav-link active" onclick="switchTab(this, 'tab-info')"><i class="fas fa-user-circle"></i> المعلومات الأساسية</div>
            <div class="nav-link" onclick="switchTab(this, 'tab-orders')"><i class="fas fa-shopping-basket"></i> سجل الطلبات</div>
        </div>

        <div id="tab-info" class="tab-pane active">
            <div class="info-grid">
                <div class="info-card"><label>الاسم بالكامل</label><p id="uName"></p></div>
                <div class="info-card"><label>رقم الجوال</label><p id="uPhone"></p></div>
                <div class="info-card" style="grid-column: span 2;"><label>البريد الإلكتروني</label><p id="uEmail"></p></div>
                <div class="info-card"><label>تاريخ الانضمام</label><p id="uJoinDate"></p></div>
                <div class="info-card"><label>العنوان</label><p id="uAddress"></p></div>
            </div>
        </div>

        <div id="tab-orders" class="tab-pane">
            <div style="max-height: 350px; overflow-y: auto; border: 1px solid var(--border); border-radius: 8px;">
                <table style="width: 100%; text-align: right; border-collapse: collapse;">
                    <thead style="position: sticky; top: 0; background: #f8fafc; z-index: 10;">
                        <tr>
                            <th style="padding:12px; font-size: 12px; border-bottom: 1px solid var(--border);">رقم الطلب</th>
                            <th style="padding:12px; font-size: 12px; border-bottom: 1px solid var(--border);">التاريخ</th>
                            <th style="padding:12px; font-size: 12px; border-bottom: 1px solid var(--border);">الإجمالي</th>
                            <th style="padding:12px; font-size: 12px; border-bottom: 1px solid var(--border);">حالة الطلب</th>
                            <th style="padding:12px; font-size: 12px; border-bottom: 1px solid var(--border);">حالة الدفع</th>

                        </tr>
                    </thead>
                    <tbody id="userOrdersList"></tbody>
                </table>
            </div>
        </div>

        <div style="padding: 20px; background: #f8fafc; text-align: left; border-top: 1px solid var(--border);">
            <button onclick="closeWizard()" style="padding: 10px 25px; border: 1px solid #cbd5e1; border-radius: 8px; background: white; color: var(--secondary); cursor: pointer; font-weight: 600;">إغلاق</button>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    let token = localStorage.getItem('admin_token');

    $(document).ready(function() {
        fetchUsers(1);

        $('#userSearch').on('keypress', function(e) {
            if(e.which == 13) fetchUsers(1);
        });

        $(window).on('click', function(e) {
            if ($(e.target).hasClass('modal-overlay')) closeWizard();
        });
    });

    function switchTab(element, tabId) {
        $('.nav-link').removeClass('active');
        $(element).addClass('active');
        $('.tab-pane').removeClass('active');
        $('#' + tabId).addClass('active');
    }

    function fetchUsers(page) {
        const phoneSearch = $('#userSearch').val();
        $.ajax({
            url: `/api/users`,
            method: 'GET',
            headers: { 'Authorization': 'Bearer ' + token },
            data: { page: page, search: phoneSearch },
            success: function(res) {
                renderTable(res.data.data);
                renderPager(res.data);
            }
        });
    }

    function renderTable(users) {
        const tbody = $('#usersList').empty();
        if(users.length === 0) {
            tbody.append('<tr><td colspan="5" style="text-align:center; padding:50px; color:#94a3b8">لم يتم العثور على مستخدمين بهذا الرقم</td></tr>');
            return;
        }
        users.forEach(u => {
            tbody.append(`
                <tr>
                    <td><div style="font-weight:700; color:var(--secondary)">${u.name}</div></td>
                    <td><div style="color:var(--text-muted); font-size:12px">${u.email}</div><div>${u.phone}</div></td>
                    <td>${new Date(u.created_at).toLocaleDateString('ar-EG')}</td>
                    <td>${u.address || '<span style="color:#cbd5e1">غير محدد</span>'}</td>
                    <td style="text-align: center;">
                        <button title="عرض التفاصيل" class="page-btn" style="width:auto; padding:0 12px; height:32px" onclick="showWizard(${u.id})">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
            `);
        });
    }

    function showWizard(id) {
        $.ajax({
            url: `/api/users/${id}`,
            method: 'GET',
            headers: { 'Authorization': 'Bearer ' + token },
            success: function(res) {
                const user = res.data;
                $('#userNameHeader').text(user.name);
                $('#uName').text(user.name);
                $('#uPhone').text(user.phone);
                $('#uEmail').text(user.email);
                $('#uJoinDate').text(new Date(user.created_at).toLocaleDateString('ar-EG'));
                $('#uAddress').text(user.address || 'لا يوجد عنوان مسجل');

                const oList = $('#userOrdersList').empty();
                if(user.orders && user.orders.length > 0) {
                    user.orders.forEach(o => {
                        oList.append(`
                            <tr>
                                <td style="padding: 12px; border-bottom: 1px solid #f1f5f9; font-weight:bold">#${o.order_number}</td>
                                <td style="padding: 12px; border-bottom: 1px solid #f1f5f9;">${new Date(o.created_at).toLocaleDateString('ar-EG')}</td>
                                <td style="padding: 12px; border-bottom: 1px solid #f1f5f9; color:var(--success); font-weight:bold">${o.total} ج.م</td>
                                <td style="padding: 12px; border-bottom: 1px solid #f1f5f9;"><span class="status-badge bg-success-light">${o.status}</span></td>
                                <td style="padding: 12px; border-bottom: 1px solid #f1f5f9;"><span class="status-badge bg-success-light">${o.payment_status}</span></td>

                            </tr>
                        `);
                    });
                } else {
                    oList.append('<tr><td colspan="4" style="text-align:center; padding: 40px; color:#94a3b8">لا توجد طلبات سابقة لهذا المستخدم</td></tr>');
                }
                $('#userWizard').css('display', 'flex');
            }
        });
    }

    function renderPager(meta) {
        const links = $('#pageLinks').empty();
        $('#pageText').text(`عرض ${meta.from} - ${meta.to} من إجمالي ${meta.total} مستخدم`);

        // زر السابق
        links.append(`<button class="page-btn" ${meta.current_page === 1 ? 'disabled' : ''} onclick="fetchUsers(${meta.current_page - 1})"><i class="fas fa-chevron-right"></i></button>`);

        // أرقام الصفحات
        for (let i = 1; i <= meta.last_page; i++) {
            if (i === 1 || i === meta.last_page || (i >= meta.current_page - 1 && i <= meta.current_page + 1)) {
                links.append(`<button class="page-btn ${i === meta.current_page ? 'active' : ''}" onclick="fetchUsers(${i})">${i}</button>`);
            } else if (i === meta.current_page - 2 || i === meta.current_page + 2) {
                links.append(`<span style="color:#cbd5e1">...</span>`);
            }
        }

        // زر التالي
        links.append(`<button class="page-btn" ${meta.current_page === meta.last_page ? 'disabled' : ''} onclick="fetchUsers(${meta.current_page + 1})"><i class="fas fa-chevron-left"></i></button>`);
    }

    function closeWizard() {
        $('.modal-overlay').hide();
        switchTab($('.nav-link').first()[0], 'tab-info');
    }
</script>
@endsection
