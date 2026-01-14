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

        .main-content { padding: 20px; background: var(--bg); min-height: 100vh; }

        /* الكروت: جعلناها تملأ المساحة العرضية بالكامل */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
            width: 100%;
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
        }

        .stat-card div h4 { color: #888; font-size: 0.95rem; margin: 0 0 10px 0; font-weight: 500; }
        .stat-card div p { font-size: 1.6rem; font-weight: 800; color: var(--admin-dark); margin: 0; }
        .stat-card i { font-size: 2.5rem; color: #f0f0f0; }

        /* منطقة الرسومات البيانية: عرض كامل وارتفاع محكوم */
        .charts-wrapper {
            display: grid;
            grid-template-columns: 1.5fr 1fr; /* الرسم البياني الطولي أكبر قليلاً */
            gap: 20px;
            width: 100%;
        }

        .chart-box {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            position: relative;
            height: 350px; /* تحديد ارتفاع مناسب جداً للعين */
        }

        .chart-box h4 { margin-bottom: 20px; color: var(--admin-dark); font-family: 'Cairo', sans-serif; }

        @media (max-width: 1100px) {
            .charts-wrapper { grid-template-columns: 1fr; }
        }
    </style>
@endsection

@section('content')
<main class="main-content">
    <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
        <div>
            <h2 style="font-weight: 800; color: var(--admin-dark);">الإحصائيات المباشرة 📊</h2>
            <p class="text-muted">متابعة أداء شيخ المندي في الوقت الفعلي</p>
        </div>
        <button class="btn btn-primary px-4 shadow" onclick="fetchAllData()" style=" border: none; border-radius: 10px;">
            <i class="fas fa-sync-alt ml-2" id="refreshIcon"></i> تحديث فوري للبيانات
        </button>
    </header>

    <div class="stats-grid">
        <div class="stat-card" style="border-right-color: var(--success);">
            <div><h4>أرباح الشهر</h4><p id="totalProfit">0 ج.م</p></div>
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
            <div><h4>ارباح اليوم</h4><p id="branchesCount">0</p></div>
            <i class="fas fa-wallet"></i>
        </div>
        <div class="stat-card" style="border-right-color: #34495e;">
            <div><h4>المصروفات</h4><p id="expensesOfMonth">0 ج.م</p></div>
            <i class="fas fa-file-invoice-dollar"></i>
        </div>
    </div>

    <div class="charts-wrapper">
        <div class="chart-box">
            <h4>📈 نمو الأرباح</h4>
            <div style="height: 250px;"><canvas id="profitChart"></canvas></div>
        </div>
        <div class="chart-box">
            <h4>🍕 مصادر الطلبات</h4>
            <div style="height: 250px;"><canvas id="orderSourceChart"></canvas></div>
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
        fetchAllData();
    });

    function fetchAllData() {
        $('#refreshIcon').addClass('fa-spin');

        $.ajax({
            url: '/api',
            method: 'GET',
            headers: { 'Authorization': 'Bearer ' + token },
            success: function(res) {
                const d = res.data;


                $('#totalProfit').text(parseFloat(d.profitOfMonth).toLocaleString() + ' ج.م') ;
                $('#onlineOrders').text(d.onlineOrdersCount || 0);
                $('#cashierOrders').text(d.cashierOrders || 0);
                $('#usersCount').text(d.countUsers || 0);
                $('#employeesCount').text(d.countAdmins || 0);
                $('#mealsCount').text(d.countMeals || 0);
                $('#branchesCount').text(parseFloat(d.profitOfDay).toLocaleString() + ' ج.م');
                $('#expensesOfMonth').text(parseFloat(d.expensesOfMonth).toLocaleString() + ' ج.م');

                updateProfitChart(d.chart_data);
                updateOrderSourceChart(d.cashierOrders, d.onlineOrdersCount);

                $('#refreshIcon').removeClass('fa-spin');
            },
            error: function() {
                $('#refreshIcon').removeClass('fa-spin');
                alert('خطأ في جلب البيانات من السيرفر');
            }
        });
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
                    backgroundColor: 'rgba(233, 69, 96, 0.05)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { grid: { display: false } }, x: { grid: { display: false } } }
            }
        });
    }

    function updateOrderSourceChart(cashier , online) {
        const ctx = document.getElementById('orderSourceChart').getContext('2d');
        if(orderSourceChart) orderSourceChart.destroy();
        orderSourceChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['أونلاين', 'كاشير'],
                datasets: [{
                    data: [online , cashier],
                    backgroundColor: ['#e94560', '#0f3460'],
                    hoverOffset: 10,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
                },
                cutout: '70%'
            }
        });
    }
</script>
@endsection
