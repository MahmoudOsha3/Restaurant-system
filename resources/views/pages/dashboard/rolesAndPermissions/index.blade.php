@extends('layout.dashboard.app')

@section('title', 'إدارة الصلاحيات والأدوار')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/dashboard/meal.css') }}">
    <style>
        :root { --primary: #e67e22; --secondary: #2c3e50; --success: #27ae60; --danger: #e74c3c; --bg: #f8f9fa; }
        
        /* Grid الصلاحيات */
        .perm-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); 
            gap: 15px; 
            margin-top: 20px; 
            max-height: 400px; 
            overflow-y: auto;
            padding: 10px;
        }

        .perm-item {
            background: white; padding: 15px; border-radius: 10px; display: flex; align-items: center; 
            justify-content: space-between; border: 1px solid #ddd; transition: 0.3s; cursor: pointer;
        }
        .perm-item:hover { border-color: var(--primary); background: #fffbf7; }
        .perm-item.selected { border-right: 5px solid var(--success); background: #f0fff4; border-color: var(--success); }

        .custom-checkbox { width: 20px; height: 20px; border-radius: 5px; border: 2px solid #ddd; display: flex; align-items: center; justify-content: center; transition: 0.3s; }
        .selected .custom-checkbox { background: var(--success); border-color: var(--success); color: white; }

        .btn-success { background: var(--success) !important; color: white; }
    </style>
@endsection

@section('content')
<main class="main-content">
    <div id="listView">
        <div class="table-header">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="roleSearchInput" placeholder="ابحث عن دور معين...">
            </div>
            <button class="btn-create btn-success" onclick="openRoleEditor()">
                <i class="fas fa-plus-circle"></i> إضافة Role جديد
            </button>
        </div>

        <table class="meals-table">
            <thead>
                <tr>
                    <th>اسم الدور (Role)</th>
                    <th>عدد الصلاحيات</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody id="rolesBody">
                </tbody>
        </table>
    </div>

    <div id="editorView" style="display: none;">
        <div style="display:flex; align-items:center; gap:20px; margin-bottom:20px">
            <button onclick="backToList()" style="border:none; background:none; cursor:pointer; font-size:1.2rem"><i class="fas fa-arrow-right"></i> رجوع</button>
            <h2 id="editorTitle">إضافة دور جديد</h2>
        </div>

        <div style="background:white; padding:25px; border-radius:15px; box-shadow:0 5px 15px rgba(0,0,0,0.05)">
            <input type="hidden" id="roleId">
            <div class="form-group" style="margin-bottom:20px">
                <label style="font-weight:bold">اسم الـ Role</label>
                <input type="text" id="roleName" class="form-control" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px" placeholder="مثال: محاسب">
            </div>

            <label style="font-weight:bold">اختر الصلاحيات</label>
            <div class="search-box" style="width:100%; margin-top:10px">
                <i class="fas fa-filter"></i>
                <input type="text" id="permFilter" placeholder="تصفية الصلاحيات...">
            </div>

            <div class="perm-grid" id="permissionsContainer">
                </div>

            <div style="margin-top:30px; display:flex; gap:10px">
                <button class="btn-create btn-success" onclick="saveRole()">حفظ البيانات</button>
                <button class="btn-create" style="background:#eee; color:#333" onclick="backToList()">إلغاء</button>
            </div>
        </div>
    </div>
</main>
@endsection

@section('js')
<script>
    let allRoles = [];
    let allPermissionsMap = {}; // لتخزين الصلاحيات الشاملة { "meal.view": "Meals View" }
    let selectedPermissions = []; // هنخزن فيها الـ slugs المختارة

    $(document).ready(function() {
        fetchRolesData();

        // فلترة الصلاحيات داخل المحرر
        $('#permFilter').on('input', function() {
            const val = $(this).val().toLowerCase();
            $('.perm-item').each(function() {
                const text = $(this).text().toLowerCase();
                $(this).toggle(text.includes(val));
            });
        });
    });

    function fetchRolesData() {
        $.ajax({
            url: 'api/role',
            method: 'GET',
            success: function(res) {
                allRoles = res.data.roles; 
                allPermissionsMap = res.data.permissions; 
                
                renderRolesTable(allRoles);
                renderPermissionsGrid(allPermissionsMap);
            }
        });
    }

    function renderRolesTable(roles) {
        const tbody = $('#rolesBody').empty();
        roles.forEach(role => {
            tbody.append(`
                <tr>
                    <td><b>${role.name}</b></td>
                    <td>
                        <span class="status-pill" style="background:#e1f5fe; color:#01579b; padding:5px 10px; border-radius:15px">
                            ${role.permissions.length} صلاحية
                        </span>
                    </td>
                    <td>
                        <button class="btn-action edit-btn" onclick="openRoleEditor(${role.id})">
                            <i class="fas fa-edit"></i> تعديل
                        </button>
                    </td>
                </tr>
            `);
        });
    }

    function renderPermissionsGrid(permsMap) {
        const container = $('#permissionsContainer').empty();
        Object.entries(permsMap).forEach(([slug, name]) => {
            container.append(`
                <div class="perm-item" data-slug="${slug}" onclick="togglePerm(this, '${slug}')">
                    <div>
                        <div style="font-weight:bold; font-size:0.85rem">${name}</div>
                        <code style="font-size:0.7rem; color:var(--primary)">${slug}</code>
                    </div>
                    <div class="custom-checkbox"><i class="fas fa-check"></i></div>
                </div>
            `);
        });
    }

    function togglePerm(el, slug) {
        $(el).toggleClass('selected');
        const index = selectedPermissions.indexOf(slug);
        if (index > -1) {
            selectedPermissions.splice(index, 1);
        } else {
            selectedPermissions.push(slug);
        }
    }

    function openRoleEditor(id = null) {
        $('#listView').hide();
        $('#editorView').show();
        $('.perm-item').removeClass('selected');
        selectedPermissions = [];

        if (id) {
            const role = allRoles.find(r => r.id === id);
            $('#editorTitle').text('تعديل صلاحيات: ' + role.name);
            $('#roleId').val(role.id);
            $('#roleName').val(role.name);
            
            // هنا بنشوف الصلاحيات اللي الـ authorize بتاعها "allow"
            role.permissions.forEach(p => {
                if(p.authorize === 'allow') {
                    selectedPermissions.push(p.permission);
                    $(`.perm-item[data-slug="${p.permission}"]`).addClass('selected');
                }
            });
        } else {
            $('#editorTitle').text('إضافة دور جديد');
            $('#roleId').val('');
            $('#roleName').val('');
        }
    }

// 1. تعديل دالة الحفظ عشان تبعت البيانات بالشكل اللي الـ Validation عاوزه
function saveRole() {
    const id = $('#roleId').val();
    const roleName = $('#roleName').val();

    if (!roleName) {
        toastr.error('يرجى إدخال اسم الدور');
        return;
    }

    // بناء كائن الصلاحيات: نلف على كل الصلاحيات المتاحة في السيستم
    // لو الـ slug موجود في selectedPermissions نبعت allow، غير كدة deny
    let permissionsPayload = {};
    
    Object.keys(allPermissionsMap).forEach(slug => {
        permissionsPayload[slug] = selectedPermissions.includes(slug) ? 'allow' : 'deny';
    });

    const data = {
        name: roleName,
        permissions: permissionsPayload, // هيبعت مثلاً {"meal.view": "allow", "meal.create": "deny", ...}
        _token: '{{ csrf_token() }}'
    };

    const url = id ? `/api/role/${id}` : '/api/role';
    // في الـ Laravel الـ Update بيفضل يتبعت POST مع إضافة _method: 'PUT'
    const method = 'POST'; 
    if(id) data['_method'] = 'PUT';

    $.ajax({
        url: url,
        method: method,
        data: data,
        success: function(res) {
            toastr.success('تم حفظ البيانات بنجاح');
            backToList();
            fetchRolesData();
        },
        error: function(xhr) {
            if(xhr.status === 422) { // أخطاء الـ Validation
                let errors = xhr.responseJSON.errors;
                Object.values(errors).flat().forEach(err => toastr.error(err));
            } else {
                toastr.error('حدث خطأ غير متوقع');
            }
        }
    });
}

    function backToList() {
        $('#editorView').hide();
        $('#listView').show();
    }
</script>
@endsection