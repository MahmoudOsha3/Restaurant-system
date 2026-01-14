@extends('layout.dashboard.app')

@section('title', 'لوحة تحكم الأداء العام - Super Admin')

@section('css')
    <style>
        :root {
            --admin-dark: #1a1a2e;
            --admin-blue: #0f3460;
            --accent: #e94560;
            --bg: #f4f7f6;
            --success: #2ecc71;
            --info: #3498db;
        }

        .main-content { padding: 20px; background: var(--bg); min-height: 100vh; font-family: 'Cairo', sans-serif; }

        /* الكروت التفاعلية */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border-right: 6px solid var(--accent);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: 0.3s ease;
        }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
        .stat-card div h4 { color: #888; font-size: 0.95rem; margin: 0 0 10px 0; font-weight: 500; }
        .stat-card div p { font-size: 1.6rem; font-weight: 800; color: var(--admin-dark); margin: 0; }
        .stat-card i { font-size: 2.5rem; color: #f0f0f0; transition: 0.3s; }
        .stat-card:hover i { color: var(--accent); opacity: 0.2; }

        /* الفلاتر */
        .filter-bar {
            background: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
            display: flex;
            align-items: flex-end;
            gap: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }

        /* الرسوم البيانية */
        .charts-wrapper {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 20px;
            width: 100%;
        }
        .chart-box {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            height: 380px;
        }

        /* =========================================
           تنسيق تقرير الطباعة الاحترافي (Official Report)
        ========================================= */
        #printable-report {
            display: none;
            padding: 50px;
            background: white;
            color: #1a1a2e;
        }

        .report-table-pro {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            border: 2px solid #1a1a2e;
        }

        .report-table-pro th {
            background-color: #1a1a2e;
            color: white;
            padding: 15px;
            text-align: right;
            font-size: 1.1rem;
        }

        .report-table-pro td {
            padding: 12px 15px;
            border: 1px solid #eee;
            font-size: 1rem;
        }

        .section-header-row {
            background-color: #f8f9fa;
            font-weight: bold;
            color: var(--accent);
            text-align: center !important;
            font-size: 1.1rem !important;
        }

        .report-footer-signature {
            margin-top: 60px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            text-align: center;
        }

        .sig-box { border-top: 1px solid #333; padding-top: 10px; font-weight: bold; width: 150px; margin: auto; }

        @media print {
            body * { visibility: hidden; }
            #printable-report, #printable-report * { visibility: visible; }
            #printable-report { position: absolute; left: 0; top: 0; width: 100%; display: block !important; }
            .no-print { display: none !important; }
            @page { size: A4; margin: 0; }
        }

        @media (max-width: 1100px) {
            .charts-wrapper { grid-template-columns: 1fr; }
            .filter-bar { flex-direction: column; align-items: stretch; }
        }
    </style>
@endsection

@section('content')
<main class="main-content">

    <header class="no-print" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 15px;">
        <div>
            <h2 style="font-weight: 800; color: var(--admin-dark); margin: 0;">لوحة الأداء العام 📊</h2>
            <p class="text-muted mb-0">نظام إدارة وتحليل بيانات "شيخ المندي"</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-dark px-4 shadow-sm" onclick="window.print()" style="border-radius: 10px;">
                <i class="fas fa-print ml-2"></i> طباعة تقرير رسمي
            </button>
            <button class="btn btn-primary px-4 shadow-sm" onclick="fetchAllData()" style="border-radius: 10px; border: none;">
                <i class="fas fa-sync-alt ml-2" id="refreshIcon"></i> تحديث فوري
            </button>
        </div>
    </header>

    <div class="filter-bar no-print" dir="rtl">
        <div style="flex: 1;">
            <label class="small fw-bold mb-1 d-block text-muted">من تاريخ:</label>
            <input type="date" id="dateFrom" class="form-control" style="border-radius: 8px;">
        </div>
        <div style="flex: 1;">
            <label class="small fw-bold mb-1 d-block text-muted">إلى تاريخ:</label>
            <input type="date" id="dateTo" class="form-control" style="border-radius: 8px;">
        </div>
        <button class="btn btn-accent text-white px-4" onclick="fetchAllData()" style="height: 40px; border-radius: 8px; background-color: var(--accent);">
            تطبيق الفلتر
        </button>
    </div>

    <div class="stats-grid no-print">
        <div class="stat-card" style="border-right-color: var(--success);">
            <div><h4>أرباح الفترة</h4><p id="totalProfit">0 ج.م</p></div>
            <i class="fas fa-wallet"></i>
        </div>
        <div class="stat-card" style="border-right-color: var(--accent);">
            <div><h4>طلبات الموقع</h4><p id="onlineOrders">0</p></div>
            <i class="fas fa-globe"></i>
        </div>
        <div class="stat-card" style="border-right-color: var(--admin-blue);">
            <div><h4>طلبات الكاشير</h4><p id="cashierOrders">0</p></div>
            <i class="fas fa-cash-register"></i>
        </div>
        <div class="stat-card" style="border-right-color: var(--info);">
            <div><h4>المستخدمين</h4><p id="usersCount">0</p></div>
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-card" style="border-right-color: #9b59b6;">
            <div><h4>الموظفين</h4><p id="employeesCount">0</p></div>
            <i class="fas fa-user-shield"></i>
        </div>
        <div class="stat-card" style="border-right-color: #f1c40f;">
            <div><h4>الوجبات المتاحة</h4><p id="mealsCount">0</p></div>
            <i class="fas fa-hamburger"></i>
        </div>
        <div class="stat-card" style="border-right-color: #e67e22;">
            <div><h4>أرباح اليوم</h4><p id="profitToday">0 ج.م</p></div>
            <i class="fas fa-coins"></i>
        </div>
        <div class="stat-card" style="border-right-color: #34495e;">
            <div><h4>المصروفات</h4><p id="expensesOfMonth">0 ج.م</p></div>
            <i class="fas fa-file-invoice-dollar"></i>
        </div>
    </div>

    <div class="charts-wrapper no-print">
        <div class="chart-box">
            <h4 class="fw-bold"><i class="fas fa-chart-line text-danger ml-2"></i> نمو الأرباح</h4>
            <div style="height: 280px;"><canvas id="profitChart"></canvas></div>
        </div>
        <div class="chart-box">
            <h4 class="fw-bold"><i class="fas fa-chart-pie text-primary ml-2"></i> توزيع الطلبات</h4>
            <div style="height: 280px;"><canvas id="orderSourceChart"></canvas></div>
        </div>
    </div>

    <div id="printable-report" dir="rtl">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 4px double var(--admin-dark); padding-bottom: 20px;">
            <div>
                <h1 style="margin: 0; font-weight: 900; color: var(--admin-dark); font-size: 2.5rem;">شيخ المندي</h1>
                <p style="margin: 5px 0; color: #666; font-size: 1.1rem;">نظام إدارة العمليات الرقمي</p>
                <p style="margin: 0; font-weight: bold; background: #eee; padding: 5px 10px; display: inline-block;">تقرير الأداء والإنتاجية الشامل</p>
            </div>
            <div style="text-align: center;">
                 <div style="border: 3px solid var(--admin-dark); padding: 15px; border-radius: 12px; font-weight: 900; font-size: 1.8rem;">
                    OFFICIAL
                 </div>
                 <p style="margin-top: 10px; font-size: 0.9rem;" id="printTimeStamp"></p>
            </div>
        </div>

        <div style="margin: 30px 0; padding: 15px; background: #f9f9f9; border-right: 5px solid var(--accent); font-size: 1.1rem;">
            <strong>الفترة الزمنية للتقرير:</strong> من <span id="printDateFrom"></span> إلى <span id="printDateTo"></span>
        </div>

        <table class="report-table-pro">
            <thead>
                <tr>
                    <th style="width: 65%;">المؤشر الإحصائي / البيان</th>
                    <th style="width: 35%; text-align: center;">القيمة المحققة</th>
                </tr>
            </thead>
            <tbody id="printTableBody">
                </tbody>
        </table>

        <div class="report-footer-signature">
            <div>
                <p>قسم الحسابات</p>
                <div style="height: 50px;"></div>
                <div class="sig-box">توقيع المعتمد</div>
            </div>
            <div>
                <p>ختم المنشأة</p>
                <div style="height: 80px; width: 80px; border: 2px dashed #ddd; margin: 10px auto; border-radius: 50%;"></div>
            </div>
            <div>
                <p>المدير العام</p>
                <div style="height: 50px;"></div>
                <div class="sig-box">توقيع المدير</div>
            </div>
        </div>

        <div style="position: fixed; bottom: 30px; width: 100%; text-align: center; font-size: 0.8rem; color: #aaa; border-top: 1px solid #eee; padding-top: 10px;">
            هذا التقرير مُولد آلياً ولا يحتاج إلى مراجعة يدوية - صادر عن منصة الإدارة الذكية
        </div>
    </div>
</main>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let profitChart, orderSourceChart;
    let token = localStorage.getItem('admin_token');

    $(document).ready(function() {
        // تعيين التواريخ الافتراضية
        let now = new Date();
        let firstDay = new Date(now.getFullYear(), now.getMonth(), 1).toISOString().split('T')[0];
        let today = now.toISOString().split('T')[0];

        $('#dateFrom').val(firstDay);
        $('#dateTo').val(today);

        fetchAllData();
    });

    function fetchAllData() {
        $('#refreshIcon').addClass('fa-spin');

        let from = $('#dateFrom').val();
        let to = $('#dateTo').val();

        $.ajax({
            url: `/api?from=${from}&to=${to}`,
            method: 'GET',
            headers: { 'Authorization': 'Bearer ' + token },
            success: function(res) {
                const d = res.data;

                // تحديث واجهة الـ Dashboard
                $('#totalProfit').text(parseFloat(d.profitOfMonth).toLocaleString() + ' ج.م') ;
                $('#onlineOrders').text(d.onlineOrdersCount || 0);
                $('#cashierOrders').text(d.cashierOrders || 0);
                $('#usersCount').text(d.countUsers || 0);
                $('#employeesCount').text(d.countAdmins || 0);
                $('#mealsCount').text(d.countMeals || 0);
                $('#profitToday').text(parseFloat(d.profitOfDay).toLocaleString() + ' ج.م');
                $('#expensesOfMonth').text(parseFloat(d.expensesOfMonth).toLocaleString() + ' ج.م');

                // تحديث الرسوم البيانية
                updateProfitChart(d.chart_data);
                updateOrderSourceChart(d.cashierOrders, d.onlineOrdersCount);

                // تجهيز التقرير الاحترافي للطباعة
                preparePrintReport(d, from, to);

                $('#refreshIcon').removeClass('fa-spin');
            },
            error: function() {
                $('#refreshIcon').removeClass('fa-spin');
                alert('خطأ في الاتصال بالسيرفر');
            }
        });
    }

    function preparePrintReport(d, from, to) {
        $('#printDateFrom').text(from);
        $('#printDateTo').text(to);
        $('#printTimeStamp').text(`تاريخ الاستخراج: ${new Date().toLocaleString('ar-EG')}`);

        let html = `
            <tr class="section-header-row"><td colspan="2">🧾 التحليل المالي والتدفقات النقدية</td></tr>
            <tr><td>إجمالي الأرباح خلال الفترة</td><td style="text-align: center; font-weight: bold;">${parseFloat(d.profitOfMonth).toLocaleString()} ج.م</td></tr>
            <tr><td>أرباح اليوم (تاريخ الإغلاق)</td><td style="text-align: center;">${parseFloat(d.profitOfDay).toLocaleString()} ج.م</td></tr>
            <tr><td>إجمالي المصروفات التشغيلية</td><td style="text-align: center; color: #e94560;">${parseFloat(d.expensesOfMonth).toLocaleString()} ج.م</td></tr>
            <tr><td style="font-weight: bold;">صافي العائد التقديري (بعد خصم المصروفات)</td><td style="text-align: center; font-weight: bold; background: #f0fff0;">${(parseFloat(d.profitOfMonth) - parseFloat(d.expensesOfMonth)).toLocaleString()} ج.م</td></tr>

            <tr class="section-header-row"><td colspan="2">📦 إحصائيات المبيعات والتشغيل</td></tr>
            <tr><td>عدد الطلبات عبر التطبيق والموقع</td><td style="text-align: center;">${d.onlineOrdersCount} طلب</td></tr>
            <tr><td>عدد الطلبات المباشرة (الكاشير)</td><td style="text-align: center;">${d.cashierOrders} طلب</td></tr>
            <tr><td>إجمالي الوجبات المتاحة في القائمة</td><td style="text-align: center;">${d.countMeals} صنف</td></tr>

            <tr class="section-header-row"><td colspan="2">👥 إدارة الكوادر والمستخدمين</td></tr>
            <tr><td>إجمالي قاعدة العملاء المسجلين</td><td style="text-align: center;">${d.countUsers} عميل</td></tr>
            <tr><td>عدد الموظفين ذوي الصلاحيات النشطة</td><td style="text-align: center;">${d.countAdmins} موظف</td></tr>
        `;

        $('#printTableBody').html(html);
    }

    function updateProfitChart(chartData) {
        const ctx = document.getElementById('profitChart').getContext('2d');
        if(profitChart) profitChart.destroy();
        profitChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.profit_labels,
                datasets: [{
                    label: 'الأرباح اليومية',
                    data: chartData.profit_values,
                    borderColor: '#e94560',
                    backgroundColor: 'rgba(233, 69, 96, 0.1)',
                    fill: true, tension: 0.4, borderWidth: 3, pointRadius: 5, pointBackgroundColor: '#e94560'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    function updateOrderSourceChart(cashier, online) {
        const ctx = document.getElementById('orderSourceChart').getContext('2d');
        if(orderSourceChart) orderSourceChart.destroy();
        orderSourceChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['أونلاين', 'كاشير'],
                datasets: [{
                    data: [online, cashier],
                    backgroundColor: ['#e94560', '#0f3460'],
                    borderWidth: 5,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }
</script>
@endsection
