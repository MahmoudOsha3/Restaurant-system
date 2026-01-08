@extends('layout.dashboard.app')

@section('title', 'إدارة المصاريف والفواتير')

@section('css')
    <style>
        :root {
            --primary: #e67e22;
            --secondary: #2c3e50;
            --success: #27ae60;
            --danger: #e74c3c;
            --bg: #f8f9fa;
            --info: #3498db;
        }

        /* تنسيق الهيدر العلوي */
        .page-header {
            background: linear-gradient(135deg, #fff 0%, #f9f9f9 100%);
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            border-right: 5px solid var(--primary);
            margin-bottom: 30px;
        }

        .page-title h2 { margin: 0; font-size: 1.6rem; font-weight: 800; color: var(--secondary); }
        .page-title p { margin: 5px 0 0 0; color: #7f8c8d; font-size: 0.9rem; }

        /* كارت الإحصائيات */
        .stat-card {
            background: white;
            padding: 15px 25px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 15px;
            border: 1px solid #edf2f7;
            transition: 0.3s;
        }
        .stat-icon {
            width: 50px; height: 50px;
            background: rgba(230, 126, 34, 0.1);
            color: var(--primary);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center; font-size: 1.4rem;
        }
        .stat-info .stat-label { display: block; font-size: 0.85rem; color: #718096; margin-bottom: 4px; }
        .stat-info .stat-value { display: block; font-size: 1.25rem; font-weight: 800; color: var(--danger); }

        /* فورم الإضافة */
        .expense-form { background: white; padding: 25px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 30px; }
        .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; align-items: flex-end; }
        .form-group { display: flex; flex-direction: column; gap: 8px; }
        .form-group label { font-weight: bold; font-size: 0.9rem; color: var(--secondary); }
        .form-group input { padding: 12px; border: 1px solid #ddd; border-radius: 8px; outline: none; transition: 0.3s; }
        .form-group input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(230, 126, 34, 0.1); }

        /* الجدول */
        .table-container { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); }
        .expense-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .expense-table th { background: #f8f9fa; padding: 15px; text-align: right; color: #718096; border-bottom: 2px solid #eee; font-size: 0.9rem; }
        .expense-table td { padding: 15px; border-bottom: 1px solid #eee; vertical-align: middle; }

        .amount-badge { color: var(--danger); font-weight: 800; font-size: 1rem; }
        .btn-add { background: var(--success); color: white; border: none; padding: 13px; border-radius: 8px; cursor: pointer; font-weight: bold; transition: 0.3s; display: flex; align-items: center; justify-content: center; gap: 8px; }
        .btn-add:hover { background: #219150; transform: translateY(-2px); }

        .btn-action { border: none; background: none; cursor: pointer; font-size: 1.1rem; padding: 5px; transition: 0.2s; }
        .btn-action:hover { transform: scale(1.2); }
    </style>
@endsection

@section('content')
<main class="main-content">
    <div class="page-header d-flex justify-content-between align-items-center">
        <div class="page-title">
            <h2><i class="fas fa-file-invoice-dollar text-primary ml-2"></i> إدارة المصاريف والفواتير</h2>
            <p>متابعة وتدقيق العمليات المالية اليومية</p>
        </div>

        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-wallet"></i></div>
            <div class="stat-info">
                <span class="stat-label">إجمالي المصاريف المسجلة</span>
                <span class="stat-value"><span id="todaySum">0</span> <small style="font-size: 0.7rem">ج.م</small></span>
            </div>
        </div>
    </div>

    <div class="expense-form">
        <h4 style="margin-top:0; color: var(--secondary); margin-bottom: 20px;">
            <i class="fas fa-plus-circle text-success ml-1"></i> تسجيل عملية صرف جديدة
        </h4>
        <form id="expenseForm" class="form-row">
            @csrf
            <input type="hidden" id="expenseId">

            <div class="form-group" style="flex: 2;">
                <label>توصيف المصروف (البيان)</label>
                <input type="text" id="description" placeholder="مثلاً: شراء خامات، فاتورة مياه..." required>
            </div>

            <div class="form-group">
                <label>المبلغ (ج.م)</label>
                <input type="number" id="amount" step="0.01" placeholder="0.00" required>
            </div>

            <button type="submit" class="btn-add" id="submitBtn">
                <i class="fas fa-check-circle"></i>
                <span id="btnText">حفظ المصروف</span>
            </button>
        </form>
    </div>

    <div class="table-container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 style="margin:0; font-weight: 700;">آخر العمليات المسجلة</h4>
            <div style="position: relative;">
                <i class="fas fa-search" style="position: absolute; right: 12px; top: 12px; color: #a4b0be;"></i>
                <input type="text" id="tableSearch" placeholder="بحث سريع..." style="padding: 10px 35px 10px 10px; border-radius: 8px; border: 1px solid #ddd; width: 280px; outline: none;">
            </div>
        </div>

        <table class="expense-table">
            <thead>
                <tr>
                    <th>التاريخ والوقت</th>
                    <th>البيان / التوصيف</th>
                    <th>المبلغ</th>
                    <th>المحاسب المسؤول</th>
                    <th style="text-align: center;">الإجراءات</th>
                </tr>
            </thead>
            <tbody id="expensesBody">
                </tbody>
        </table>
    </div>
</main>
@endsection

@section('js')
<script>
    let token = localStorage.getItem('admin_token') ;
    $(document).ready(function() {
        fetchExpenses();

        // الحفظ (إضافة أو تعديل)
        $('#expenseForm').on('submit', function(e) {
            e.preventDefault();
            saveExpense();
        });

        // البحث في الجدول
        $("#tableSearch").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#expensesBody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });

    function fetchExpenses() {
        $.ajax({
            url: '/api/invoice',
            method: 'GET',
            headers : {
                'Authorization': 'Bearer ' + token,
            } ,
            success: function(res) {
                renderTable(res.data);
                calculateTotal(res.data);
            }
        });
    }

    function renderTable(data) {
        const tbody = $('#expensesBody').empty();
        data.forEach(item => {
            tbody.append(`
                <tr>
                    <td>
                        <div style="display: flex; flex-direction: column;">
                            <span style="font-weight: 600; color: var(--secondary);">
                                ${new Date(item.created_at).toLocaleDateString('ar-EG', { day: 'numeric', month: 'short', year: 'numeric' })}
                            </span>
                            <small class="text-muted" style="font-size: 0.75rem;">
                                <i class="far fa-clock ml-1"></i> ${new Date(item.created_at).toLocaleTimeString('ar-EG', { hour: '2-digit', minute: '2-digit' })}
                            </small>
                        </div>
                    </td>
                    <td><strong style="color: #2f3542;">${item.description}</strong></td>
                    <td><span class="amount-badge">${parseFloat(item.amount).toLocaleString()} ج.م</span></td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div style="width: 30px; height: 30px; background: #eee; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-left: 8px;">
                                <i class="fas fa-user-tie" style="font-size: 0.8rem; color: #747d8c;"></i>
                            </div>
                            <span>${item.admin ? item.admin.name : 'النظام'}</span>
                        </div>
                    </td>
                    <td style="text-align: center;">
                        <button onclick='editExpense(${JSON.stringify(item)})' class="btn-action" style="color: var(--info)" title="تعديل">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteExpense(${item.id})" class="btn-action" style="color: var(--danger)" title="حذف">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
            `);
        });
    }

    function saveExpense() {
        const id = $('#expenseId').val();
        const data = {
            description: $('#description').val(),
            amount: $('#amount').val(),
            _token: '{{ csrf_token() }}'
        };

        const url = id ? `/api/invoice/${id}` : '/api/invoice';
        const method = id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            method: method,
            data: data,
            success: function() {
                toastr.success('تم الحفظ بنجاح');
                resetForm();
                fetchExpenses();
            },
            error: function() {
                toastr.error('عذراً، حدث خطأ ما');
            }
        });
    }

    function editExpense(item) {
        $('#expenseId').val(item.id);
        $('#description').val(item.description);
        $('#amount').val(item.amount);

        $('#btnText').text('تحديث البيانات');
        $('#submitBtn').css('background', 'var(--info)');
        $('html, body').animate({ scrollTop: 0 }, 'slow');
        $('#description').focus();
    }

    function deleteExpense(id) {
        if(confirm('هل أنت متأكد من حذف هذه الفاتورة؟')) {
            $.ajax({
                url: `/api/invoice/${id}`,
                method: 'DELETE',
                headers : {
                    'Authorization': 'Bearer ' + token,
                } ,
                data: { _token: '{{ csrf_token() }}' },
                success: function() {
                    toastr.warning('تم حذف السجل');
                    fetchExpenses();
                }
            });
        }
    }

    function resetForm() {
        $('#expenseId').val('');
        $('#expenseForm')[0].reset();
        $('#btnText').text('حفظ المصروف');
        $('#submitBtn').css('background', 'var(--success)');
    }

    function calculateTotal(data) {
        let sum = data.reduce((acc, curr) => acc + parseFloat(curr.amount), 0);
        // عداد انيميشن للرقم
        $({ Counter: 0 }).animate({ Counter: sum }, {
            duration: 1000,
            step: function () {
                $('#todaySum').text(Math.ceil(this.Counter).toLocaleString());
            }
        });
    }
</script>
@endsection
