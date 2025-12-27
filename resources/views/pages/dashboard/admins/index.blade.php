@extends('layout.dashboard.app')

@section('title', 'إدارة المستخدمين')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/dashboard/meal.css') }}">
    <style>
        :root { --primary: #e67e22; --secondary: #2c3e50; --success: #27ae60; --danger: #e74c3c; --info: #3498db; }

        /* تنسيق الترقيم الصفحي (نفس الكود السابق) */
        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            background: white;
            padding: 15px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .page-link {
            padding: 8px 15px;
            border: 1px solid #eee;
            background: white;
            cursor: pointer;
            border-radius: 6px;
            margin: 0 2px;
            transition: 0.3s;
        }
        .page-link.active { background: var(--primary); color: white; border-color: var(--primary); }
        .page-link:disabled { opacity: 0.5; cursor: not-allowed; }

        .role-badge { background: #eef2ff; color: #4338ca; padding: 4px 10px; border-radius: 6px; font-size: 0.85rem; font-weight: bold; }
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); align-items: center; justify-content: center; z-index: 1000; padding: 20px; }
        .modal-box { background: white; padding: 25px; border-radius: 15px; width: 100%; max-width: 550px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
    </style>
@endsection

@section('content')
<main class="main-content">
    <div class="table-header">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="ابحث عن مستخدم (اسم، إيميل، هاتف)...">
        </div>
        <button class="btn-create" onclick="openFormModal()">
            <i class="fas fa-user-plus"></i> إضافة مستخدم جديد
        </button>
    </div>

    <table class="meals-table">
        <thead>
            <tr>
                <th>الاسم</th>
                <th>رقم الهاتف</th>
                <th>البريد الإلكتروني</th>
                <th>العنوان</th>
                <th>الصلاحية (Role)</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody id="adminsBody">
            </tbody>
    </table>

    <div class="pagination-container">
        <div id="paginationInfo" style="color:#666; font-size: 0.9rem;"></div>
        <div id="paginationLinks"></div>
    </div>
</main>

<div id="modalForm" class="modal-overlay">
    <div class="modal-box">
        <h3 id="formTitle" style="margin-top:0; color:var(--secondary)">إضافة مستخدم جديد</h3>
        <input type="hidden" id="adminId">
        <div class="form-grid">
            <div class="form-group">
                <label>الاسم الكامل</label>
                <input type="text" id="adminName">
            </div>
            <div class="form-group">
                <label>رقم الهاتف</label>
                <input type="text" id="adminPhone">
            </div>
            <div class="form-group full-width">
                <label>البريد الإلكتروني</label>
                <input type="email" id="adminEmail">
            </div>
            <div class="form-group">
                <label>العنوان</label>
                <input type="text" id="adminAddress">
            </div>
            <div class="form-group">
                <label>الصلاحية</label>
                <select id="adminRole">
                    </select>
            </div>
            <div class="form-group full-width">
                <label>كلمة المرور </label>
                <input type="password" id="adminPassword">
            </div>
        </div>
        <div style="display:flex; gap:10px; margin-top:25px">
            <button class="btn-create" style="flex:2" onclick="saveAdmin()">حفظ البيانات</button>
            <button onclick="closeModals()" style="flex:1; border:none; background:#eee; border-radius:8px; cursor:pointer">إلغاء</button>
        </div>
    </div>
</div>

<div id="modalDelete" class="modal-overlay">
    <div class="modal-box" style="max-width:350px; text-align:center">
        <i class="fas fa-exclamation-triangle fa-3x" style="color:var(--danger)"></i>
        <h3>هل أنت متأكد؟</h3>
        <p>سيتم حذف المستخدم <b id="deleteName"></b> نهائياً.</p>
        <input type="hidden" id="deleteId">
        <div style="display:flex; gap:10px; margin-top:20px">
            <button class="btn-create" style="background:var(--danger); flex:1" onclick="confirmDelete()">حذف</button>
            <button class="btn-create" style="background:#eee; color:#333; flex:1" onclick="closeModals()">تراجع</button>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    let adminsData = [];
    let rolesList = [];
    let currentP = 1;
    let token = localStorage.getItem('admin_token')

    $(document).ready(function() {
        fetchAdmins(1);
        fetchRoles();

        $('#searchInput').on('input', function() {
            const query = $(this).val().toLowerCase();
            const filtered = adminsData.filter(a =>
                a.name.toLowerCase().includes(query) ||
                a.email.toLowerCase().includes(query) ||
                (a.phone && a.phone.includes(query))
            );
            renderTable(filtered);
        });
    });

    function fetchAdmins(page) {
        currentP = page;
        $.ajax({
            url: `/api/admins?page=${page}`,
            method: 'GET',
            headers : {
                'Authorization': 'Bearer ' + token,
            } ,
            success: function(res) {
                adminsData = res.data.data;
                renderTable(adminsData);
                renderPagination(res.data);
            }
        });
    }

    function fetchRoles() {
        $.ajax({
            url: '/api/get/roles',
            method: 'GET',
            headers : {
                'Authorization': 'Bearer ' + token,
            } ,
            success: function(res) {
                rolesList = res.data;
                let options = '<option value="">اختر صلاحية</option>';
                rolesList.forEach(r => options += `<option value="${r.id}">${r.name}</option>`);
                $('#adminRole').html(options);
            }
        });
    }

    function renderTable(data) {
        const tbody = $('#adminsBody').empty();
        if(data.length === 0) {
            tbody.append('<tr><td colspan="6" style="text-align:center">لا يوجد مستخدمين</td></tr>');
            return;
        }
        data.forEach(a => {
            tbody.append(`
                <tr>
                    <td><b>${a.name}</b></td>
                    <td>${a.phone || '---'}</td>
                    <td>${a.email}</td>
                    <td>${a.address || '---'}</td>
                    <td><span class="role-badge">${a.role ? a.role.name : 'مستخدم'}</span></td>
                    <td>
                        <button class="btn-action edit-btn" onclick="openEditModal(${a.id})"><i class="fas fa-edit"></i></button>
                        <button class="btn-action delete-btn" onclick="openDeleteModal(${a.id}, '${a.name}')"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `);
        });
    }

    function renderPagination(meta) {
        const links = $('#paginationLinks').empty();
        $('#paginationInfo').text(`عرض ${meta.from || 0}-${meta.to || 0} من ${meta.total} مستخدم`);

        links.append(`<button class="page-link" ${meta.current_page === 1 ? 'disabled' : ''} onclick="fetchAdmins(${meta.current_page - 1})">السابق</button>`);

        for (let i = 1; i <= meta.last_page; i++) {
            if (i === 1 || i === meta.last_page || (i >= meta.current_page - 1 && i <= meta.current_page + 1)) {
                links.append(`<button class="page-link ${i === meta.current_page ? 'active' : ''}" onclick="fetchAdmins(${i})">${i}</button>`);
            }
        }

        links.append(`<button class="page-link" ${meta.current_page === meta.last_page ? 'disabled' : ''} onclick="fetchAdmins(${meta.current_page + 1})">التالي</button>`);
    }

    // فتح الـ Modal للإضافة
    function openFormModal() {
        $('#formTitle').text('إضافة مستخدم جديد');
        $('#adminId').val('');
        $('#adminName, #adminPhone, #adminEmail, #adminAddress, #adminPassword').val('');
        $('#adminRole').val('');
        $('#modalForm').css('display', 'flex');
    }

    // فتح الـ Modal للتعديل
    function openEditModal(id) {
        const a = adminsData.find(x => x.id === id);
        $('#formTitle').text('تعديل بيانات: ' + a.name);
        $('#adminId').val(a.id);
        $('#adminName').val(a.name);
        $('#adminPhone').val(a.phone);
        $('#adminEmail').val(a.email);
        $('#adminAddress').val(a.address);
        $('#adminRole').val(a.role_id);
        $('#adminPassword').val(''); // نترك كلمة المرور فارغة
        $('#modalForm').css('display', 'flex');
    }

    function saveAdmin() {
        const id = $('#adminId').val();
        const payload = {
            name: $('#adminName').val(),
            phone: $('#adminPhone').val(),
            email: $('#adminEmail').val(),
            address: $('#adminAddress').val(),
            role_id: $('#adminRole').val(),
            password: $('#adminPassword').val(),
            _token: '{{ csrf_token() }}'
        };

        const url = id ? `/api/admins/${id}` : '/api/admins';
        const method = id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            method: method,
            data: payload,
            success: function() {
                toastr.success('تم حفظ البيانات بنجاح');
                closeModals();
                fetchAdmins(currentP);
            },
            error: function(xhr) {
                alert(xhr.responseJSON?.message || 'خطأ في الحفظ');
            }
        });
    }

    function openDeleteModal(id, name) {
        $('#deleteId').val(id);
        $('#deleteName').text(name);
        $('#modalDelete').css('display', 'flex');
    }

    function confirmDelete() {
        const id = $('#deleteId').val();
        $.ajax({
            url: `/api/admins/${id}`,
            method: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function() {
                toastr.success('تم الحذف بنجاح');
                closeModals();
                fetchAdmins(currentP);
            }
        });
    }

    function closeModals() { $('.modal-overlay').hide(); }
</script>
@endsection
