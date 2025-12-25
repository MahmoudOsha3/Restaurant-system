@extends('layout.dashboard.app')

@section('title', 'لوحة تحكم الأداء العام - Super Admin')

@section('css')
    <style>
        :root {
            --admin-dark: #1a1a2e;
            --admin-blue: #0f3460;
            --accent: #e94560;
            --bg: #f4f7f6;
        }

        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px; }
        .stat-card {
            background: white; padding: 20px; border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-right: 5px solid var(--accent);
        }
        .stat-card h4 { color: #666; font-size: 0.9rem; margin-bottom: 10px; margin-top: 0; }
        .stat-card p { font-size: 1.5rem; font-weight: bold; color: var(--admin-dark); margin: 0; }

        /* تعديل لعرض الشارت بعرض كامل الصفحة */
        .charts-section { display: block; margin-bottom: 30px; }
        .chart-container { background: white; padding: 20px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); height: 400px; }

        .orders-table { width: 100%; border-collapse: collapse; background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .orders-table th, .orders-table td { padding: 15px; text-align: right; border-bottom: 1px solid #eee; }
        .orders-table th { background: var(--admin-blue); color: white; }

        @media (max-width: 1024px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
@endsection

@section('content')
<main class="main-content">
    <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h2 class="mb-0">لوحة تحكم الأداء العام 📊</h2>
            <p class="text-muted">مرحباً: المدير العام <i class="fas fa-crown" style="color: gold;"></i></p>
        </div>
        <button class="btn btn-light shadow-sm" onclick="fetchAllData()">
            <i class="fas fa-sync-alt ml-1" id="refreshIcon"></i> تحديث فوري
        </button>
    </header>

    <div class="stats-grid">
        <div class="stat-card">
            <h4>إجمالي الأرباح الشهر</h4>
            <p id="totalProfit">0 ج.م</p>
        </div>
        <div class="stat-card">
            <h4>عدد الطلبات في الشهر</h4>
            <p id="ordersCount">0 طلب</p>
        </div>
        <div class="stat-card">
            <h4>عدد الوجبات المتاحة</h4>
            <p id="mealsCount">0 وجبات</p>
        </div>
        <div class="stat-card">
            <h4>إجمالي أرباح اليوم</h4>
            <p id="todayProfit">0 ج.م</p>
        </div>
    </div>

    <div class="charts-section">
        <div class="chart-container">
            <h4 style="margin-bottom: 15px;">نمو الأرباح (آخر 7 ساعات)</h4>
            <canvas id="profitChart"></canvas>
        </div>
    </div>

    <h3>تفاصيل العمليات المنفذة مؤخراً</h3>
    <table class="orders-table">
        <thead>
            <tr>
                <th>رقم الطلب</th>
                <th>بواسطة</th>
                <th>الوقت</th>
                <th>تفاصيل</th>
                <th>المبلغ</th>
            </tr>
        </thead>
        <tbody id="ordersBody"></tbody>
    </table>
</main>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let profitChart;

    $(document).ready(function() {
        fetchAllData();
    });

    function fetchAllData() {
        $('#refreshIcon').addClass('fa-spin');

        $.ajax({
            url: '/api', // تأكد من كتابة المسار الصحيح هنا
            method: 'GET',
            success: function(res) {
                // بما إننا بنستخدم successApi، البيانات موجودة داخل res.data
                const d = res.data;

                // 1. تحديث الأرقام
                $('#totalProfit').text(parseFloat(d.profitOfMonth).toLocaleString() + ' ج.م');
                $('#ordersCount').text(d.countOrders + ' طلب');
                $('#mealsCount').text(d.countMeals + ' وجبات');
                $('#todayProfit').text(parseFloat(d.profitOfDay).toLocaleString() + ' ج.م');

                // 2. تحديث الجدول
                renderTable(d.recent_orders);

                // 3. تحديث الرسم البياني
                updateProfitChart(d.chart_data);

                $('#refreshIcon').removeClass('fa-spin');
            },
            error: function() {
                toastr.error('فشل في جلب البيانات');
                $('#refreshIcon').removeClass('fa-spin');
            }
        });
    }

    function renderTable(orders) {
        const tbody = $('#ordersBody').empty();
        orders.forEach(order => {
            const timeFormatted = new Date(order.created_at).toLocaleTimeString('ar-EG', {hour: '2-digit', minute:'2-digit'});

            tbody.append(`
                <tr>
                    <td>#${order.id}</td>
                    <td style="font-weight: bold; color: var(--admin-blue);">${order.cashier}</td>
                    <td>${timeFormatted}</td>
                    <td style="font-size: 0.85rem; color: #666;">${order.summary}</td>
                    <td style="font-weight: bold;">${order.amount} ج.م</td>
                </tr>
            `);
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
                    label: 'الأرباح الفورية',
                    data: chartData.profit_values,
                    borderColor: '#e94560',
                    backgroundColor: 'rgba(233, 69, 96, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });
    }
</script>
@endsection
