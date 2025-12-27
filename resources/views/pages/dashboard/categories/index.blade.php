@extends('layout.dashboard.app')

@section('title' , 'الاقسام')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/dashboard/categories.css') }}">
@endsection


@section('content')
    <main class="main-content">
        <div class="table-header">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="ابحث عن قسم...">
            </div>
            <button class="btn-create" onclick="openFormModal()">
                <i class="fas fa-plus"></i> إضافة قسم جديد
            </button>
        </div>

        <table class="categories-table">
            <thead>
                <tr>
                    <th>عنوان القسم</th>
                    <th>عدد الوجبات</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody id="categoriesBody">
                </tbody>
        </table>
    </main>

    <div id="modalForm" class="modal-overlay">
        <div class="modal-box">
            <h3 id="modalTitle">إضافة قسم جديد</h3>
            <hr style="opacity:0.1; margin-bottom:20px">
            <input type="hidden" id="catId">
            <div class="form-group">
                <label>اسم القسم</label>
                <input type="text" id="catTitle" placeholder="مثلاً: مشويات، بيتزا...">
            </div>
            <div style="display:flex; gap:10px">
                <button class="btn-create" style="flex:2" onclick="saveCategory()">حفظ القسم</button>
                <button onclick="closeModals()" style="flex:1; border:none; background:#eee; border-radius:8px; cursor:pointer">إلغاء</button>
            </div>
        </div>
    </div>

    <div id="modalView" class="modal-overlay">
        <div class="modal-box" style="border-right: 5px solid var(--info);">
            <h3>تفاصيل القسم</h3>
            <p style="margin-top:20px"><b>اسم القسم:</b> <span id="viewTitle"></span></p>
            <p><b>عدد الوجبات المندرجة تحت هذا القسم:</b> <span id="viewCount"></span> وجبة</p>
            <button onclick="closeModals()" style="width:100%; padding:10px; margin-top:15px; border:none; background:#eee; border-radius:8px; cursor:pointer">إغلاق</button>
        </div>
    </div>

    <div id="modalDelete" class="modal-overlay">
        <div class="modal-box" style="width:300px; text-align:center">
            <i class="fas fa-exclamation-triangle fa-3x" style="color:var(--danger)"></i>
            <h3 style="margin-top:15px">حذف القسم؟</h3>
            <p>هل أنت متأكد من حذف قسم <b id="deleteTitle"></b>؟</p>
            <input type="hidden" id="deleteId">
            <div style="display:flex; gap:10px; margin-top:20px">
                <button onclick="confirmDelete()" style="flex:1; background:var(--danger); color:white; border:none; padding:10px; border-radius:8px; cursor:pointer">نعم، احذف</button>
                <button onclick="closeModals()" style="flex:1; background:#eee; border:none; padding:10px; border-radius:8px; cursor:pointer">تراجع</button>
            </div>
        </div>
    </div>
@endsection


@section('js')
<script>
    // بيانات تجريبية (سيتم استبدالها بـ AJAX)
    let categories = [];
    let token = localStorage.getItem('admin_token')

    $(document).ready(function() {
        fetchCategories();

        // البحث الفوري
        $('#searchInput').on('input', function() {
            const query = $(this).val().toLowerCase();
            const filtered = categories.filter(c => c.title.toLowerCase().includes(query));
            renderTable(filtered);
        });
    });

    function fetchCategories(){
        $.ajax({
            url : "api/category" ,
            method : "GET" ,
            headers : {
                'Authorization': 'Bearer ' + token,
            } ,
            success : function(res){
                categories = res.data ;
                renderTable(categories) ;
            } ,
            error : function(xhr){
                alert(xhr.responseJSON?.message || 'حدث خطأ');
            } ,
        });
    }



    // وظيفة رسم الجدول
    function renderTable(categories) {
        const tbody = $('#categoriesBody');
        tbody.empty();

        categories.forEach(cat => {
            tbody.append(`
                <tr>
                    <td style="font-weight: 600;">
                        <i class="fas fa-folder-open" style="color:var(--primary); margin-left:8px"></i>
                        ${cat.title}
                    </td>
                        <td>${cat.meals_count ?? 0} وجبات</td>
                    <td>
                        <button class="btn-action view-btn" onclick="openViewModal(${cat.id})"><i class="fas fa-eye"></i></button>
                        <button class="btn-action edit-btn" onclick="openFormModal(${cat.id})"><i class="fas fa-edit"></i></button>
                        <button class="btn-action delete-btn" onclick="openDeleteModal(${cat.id})"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `);
        });
    }

    // فتح نافذة الإضافة/التعديل
    function openFormModal(id = null) {
        if (id) {
            const cat = categories.find(c => c.id === id);
            $('#modalTitle').text('تعديل القسم');
            $('#catId').val(cat.id);
            $('#catTitle').val(cat.title);
        } else {
            $('#modalTitle').text('إضافة قسم جديد');
            $('#catId').val('');
            $('#catTitle').val('');
        }
        $('#modalForm').css('display', 'flex');
    }

    // store and update
    function saveCategory() {
        const id = $('#catId').val();
        const title = $('#catTitle').val();

        if (!title) return toastr.warning("يرجى إدخال اسم القسم");
        $.ajax({
            url: id ? '/api/category/' + id : '/api/category' ,
            headers: {
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + token,
            },
            method: id ? 'PUT' : 'POST' ,
            data: { title : title } ,
            success: function(response) {
                if(id){ // update
                    const index = categories.findIndex( category => category.id == id ) ;
                    categories[index].title = title ;
                    toastr.success("تم تحديث القسم بنجاح ✅");
                }else{ // create
                    categories.unshift(response.data);
                    toastr.success("تم إضافة القسم بنجاح 🎉");
                }
                renderTable(categories);
                closeModals();
            },
            error: function (xhr) {
                alert(xhr.responseJSON?.message || 'حدث خطأ');
            }
        });
    }

    // فتح نافذة العرض
    function openViewModal(id) {
        const cat = categories.find(c => c.id === id);
        $('#viewTitle').text(cat.title);
        $('#viewCount').text(cat.itemsCount);
        $('#modalView').css('display', 'flex');
    }

    // فتح نافذة الحذف
    function openDeleteModal(id) {
        const cat = categories.find(category => category.id === id);
        $('#deleteTitle').text(cat.title);
        $('#deleteId').val(cat.id);
        $('#modalDelete').css('display', 'flex'); // convert from hidden to flex
    }

    // تأكيد الحذف (AJAX DELETE)
    function confirmDelete() {
        const id = $('#deleteId').val();

        $.ajax({
            url : "/api/category/" + id ,
            method : "DELETE" ,
            headers : {
                'Accept' : 'application/json',
                'Authorization': 'Bearer ' + token,
            } ,
            success : function(){
                categories = categories.filter(category => category.id != id) ;
                renderTable(categories) ;
                closeModals() ;
                toastr.success("تم حذف القسم بنجاح 🗑️");
            } ,
            error : function(){
                toastr.error("فشل حذف القسم");
            }
        }) ;

        categories = categories.filter(c => c.id != id);
        renderTable(categories);
        closeModals();
    }

    // إغلاق جميع النوافذ
    function closeModals() {
        $('.modal-overlay').hide();
    }
</script>
@endsection
