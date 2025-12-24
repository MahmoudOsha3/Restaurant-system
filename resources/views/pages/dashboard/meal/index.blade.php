@extends('layout.dashboard.app')

@section('title', 'إدارة الوجبات')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/dashboard/meal.css') }}">
    <style>
        /* تأكيد إخفاء النوافذ المنبثقة عند البداية */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }
        /* تنسيق إضافي لجدول البيانات */
        #mealsBody tr td { vertical-align: middle; }
    </style>
@endsection

@section('content')
<main class="main-content">
    <div class="table-header">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="ابحث عن وجبة...">
        </div>
        <button class="btn-create" onclick="openFormModal()">
            <i class="fas fa-plus"></i> إضافة وجبة جديدة
        </button>
    </div>

    <table class="meals-table">
        <thead>
            <tr>
                <th>الوجبة</th>
                <th>القسم</th>
                <th>السعر</th>
                <th>الحالة</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody id="mealsBody">
            </tbody>
    </table>
</main>

<div id="modalForm" class="modal-overlay">
    <div class="modal-box">
        <h3 id="formTitle">إضافة وجبة جديدة</h3>
        <input type="hidden" id="mealId">
        <div class="form-grid">
            <div class="form-group">
                <label>اسم الوجبة</label>
                <input type="text" id="mealTitle">
            </div>
            <div class="form-group">
                <label>القسم</label>
                <select id="mealCategoryId">

                    {{-- categories --}}
                </select>
            </div>
            <div class="form-group full-width">
                <label>وصف الوجبة</label>
                <textarea id="mealDescription" rows="2"></textarea>
            </div>
            <div class="form-group">
                <label>السعر الأساسي</label>
                <input type="number" id="mealPrice">
            </div>
            <div class="form-group">
                <label>السعر التخفيضي</label>
                <input type="number" id="compare_price">
            </div>
            <div class="form-group">
                <label>وقت التحضير (دقيقة)</label>
                <input type="number" id="mealPrepTime">
            </div>
            <div class="form-group">
                <label>الحالة</label>
                <select id="mealActive">
                    <option selected value="active">نشط</option>
                    <option value="inactive">مخفي</option>
                </select>
            </div>
            <div class="form-group full-width">
                <label>صورة الوجبة</label>
                <input type="file" id="mealImage">
            </div>
        </div>
        <div style="display:flex; gap:10px; margin-top:25px">
            <button class="btn-create" style="flex:2" onclick="saveMeal()">حفظ البيانات</button>
            <button onclick="closeModals()" style="flex:1; border:none; border-radius:8px; cursor:pointer">إلغاء</button>
        </div>
    </div>
</div>

<div id="modalView" class="modal-overlay">
    <div class="modal-box" style="border-right: 5px solid var(--info);">
        <h2>تفاصيل الوجبة والإحصائيات</h2>
        <hr style="margin:15px 0; opacity:0.1">
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px; margin-bottom:20px">
            <div style="background:#f0f7ff; padding:15px; border-radius:10px; text-align:center">
                <small>عدد المبيعات</small>
                <div style="font-size:1.5rem; font-weight:bold; color:var(--info)" id="viewSalesCount">0</div>
            </div>
            <div style="background:#f0fff4; padding:15px; border-radius:10px; text-align:center">
                <small>إجمالي الدخل</small>
                <div style="font-size:1.5rem; font-weight:bold; color:var(--success)" id="viewTotalIncome">0 ج.م</div>
            </div>
        </div>
        <p><b>الوصف:</b> <span id="viewDescription"></span></p>
        <p><b>وقت التحضير:</b> <span id="viewPrepTime"></span> دقيقة</p>
        <button onclick="closeModals()" style="width:100%; padding:10px; margin-top:15px; border:none; background:#eee; border-radius:8px; cursor:pointer">إغلاق</button>
    </div>
</div>

<div id="modalDelete" class="modal-overlay">
    <div class="modal-box" style="width:300px; text-align:center">
        <i class="fas fa-trash-alt fa-3x" style="color:var(--danger)"></i>
        <h3 style="margin-top:15px">حذف الوجبة؟</h3>
        <p>هل أنت متأكد من حذف <b id="deleteTargetName"></b>؟</p>
        <input type="hidden" id="deleteTargetId">
        <div style="display:flex; gap:10px; margin-top:20px">
            <button onclick="confirmDelete()" style="flex:1; background:var(--danger); color:white; border:none; padding:10px; border-radius:8px; cursor:pointer">نعم</button>
            <button onclick="closeModals()" style="flex:1; background:#eee; border:none; padding:10px; border-radius:8px; cursor:pointer">لا</button>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    let meals = [];
    let categories = [] ;
    let categoriesView = $('#mealCategoryId') ;

    $(document).ready(function() {
        fetchMeals() ;

        // وظيفة البحث الفوري
        $('#searchInput').on('input', function() {
            const query = $(this).val().toLowerCase();
            const filtered = meals.filter(m =>
                m.title.toLowerCase().includes(query) || m.category.title.toLowerCase().includes(query)
            );
            renderTable(filtered);
        });
    });

    function fetchMeals(){
        $.ajax({
            url : "api/meal" ,
            method : "GET" ,
            headers : {
                'Accept' : 'application/json' ,
            } ,
            success : function(response){
                meals = response.data.data ; // paginate
                renderTable(meals) ;
            } ,
            error: function(xhr){
                alert(xhr.responseJSON?.message || 'حدث خطأ');
            }

        }) ;
    }

    function fetchCategories(){

            return $.ajax({
                url : "api/category" ,
                method : "GET" ,
                headers : {
                    'Accept' : 'application/json' ,
                } ,
                success : function(response){
                    categories = response.data ; // paginate
                } ,
                error: function(xhr){
                    alert(xhr.responseJSON?.message || 'حدث خطأ');
                }
            }) ;
    }

    function renderTable(meals) {
        const tbody = $('#mealsBody');
        tbody.empty();
        meals.forEach(meal => {
            // const priceDisplay = meal.discountPrice ? meal.discountPrice : meal.price;
            const statusText = meal.status == 'active' ? 'نشط' : 'مخفي';
            const statusColor = meal.status == 'active' ? 'green' : 'red';

            tbody.append(`
                <tr>
                    <td style="font-weight: 600;">${meal.title}</td>
                    <td>${meal.category.title}</td>
                    <td><b>${meal.price} ج.م</b></td>
                    <td><span style="color: ${statusColor}; font-weight: bold;">${statusText}</span></td>
                    <td>
                        <button class="btn-action view-btn" onclick="openViewModal(${meal.id})"><i class="fas fa-eye"></i></button>
                        <button class="btn-action edit-btn" onclick="openFormModal(${meal.id})"><i class="fas fa-edit"></i></button>
                        <button class="btn-action delete-btn" onclick="openDeleteModal(${meal.id})"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `);
        });
    }

    function renderCategories(selectedId = null) {
        categoriesView.empty();
        categoriesView.append(`<option value="">اختر القسم</option>`);

        categories.forEach(category => {
            categoriesView.append(`
                <option value="${category.id}" ${selectedId == category.id ? 'selected' : ''}>
                    ${category.title}
                </option>
            `);
        });
    }

    async function openFormModal(id = null) {

        if (categories.length === 0) {
            await fetchCategories();
        }

        if (id) {
            const meal = meals.find(m => m.id === id);
            renderCategories(meal.category_id);
            $('#formTitle').text('تعديل وجبة');
            $('#mealId').val(meal.id);
            $('#mealTitle').val(meal.title);
            $('#mealCategoryId').val(meal.category_id);
            $('#mealDescription').val(meal.description);
            $('#mealPrice').val(meal.price);
            $('#compare_price').val(meal.compare_price);
            $('#mealPrepTime').val(meal.preparation_time);
            $('#mealActive').val(meal.status);
        } else {
            renderCategories();
            $('#formTitle').text('إضافة وجبة جديدة');
            $('#mealId').val('');
            $('#mealTitle').val('');
            $('#mealCategoryId').val('');
            $('#mealDescription').val('');
            $('#mealPrice').val(0);
            $('#compare_price').val('');
            $('#mealPrepTime').val(10);
            $('#mealActive').val('active');
        }
        $('#modalForm').css('display', 'flex');
    }

    function saveMeal() {
        const id = $('#mealId').val();
        let formData = new FormData();
        formData.append('title', $('#mealTitle').val());
        formData.append('description', $('#mealDescription').val());
        formData.append('price', $('#mealPrice').val());
        formData.append('compare_price', $('#compare_price').val());
        formData.append('preparation_time', $('#mealPrepTime').val());
        formData.append('category_id', $('#mealCategoryId').val());
        formData.append('status', $('#mealActive').val());

        let image = $('#mealImage')[0].files[0];
        if (image) {
            formData.append('image', image);
        }

        if (id) {
            formData.append('_method', 'PUT');
        }


        $.ajax({
            url: id ? '/api/meal/' + id : '/api/meal',
            type: 'POST' ,
            headers : {'Accept' : 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            } ,
            data:formData,
            processData: false,
            contentType: false,
            success: function(response) {

                toastr.success('تم ادخال البيانات بنجاح');
                closeModals();
                fetchMeals();
            },
            error: function(xhr){
                console.log(xhr.responseJSON?.message);
            }
        });
    }

    function openViewModal(id) {
        const meal = meals.find(m => m.id === id);
        const income = (meal.salesCount || 0) * (meal.discountPrice || meal.price);

        $('#viewSalesCount').text(meal.salesCount || 0);
        $('#viewTotalIncome').text(income + ' ج.م');
        $('#viewDescription').text(meal.description || 'لا يوجد وصف');
        $('#viewPrepTime').text(meal.prepTime);
        $('#modalView').css('display', 'flex');
    }

    function openDeleteModal(id) {
        const meal = meals.find(m => m.id === id);
        $('#deleteTargetName').text(meal.title);
        $('#deleteTargetId').val(meal.id);
        $('#modalDelete').css('display', 'flex');
    }

    function confirmDelete() {
        const id = $('#deleteTargetId').val();
        $.ajax({
            url : "/api/meal/" + id ,
            method : "DELETE" ,
            headers : {'Accept' : 'application/json' } ,
            success : function(){
                meals = meals.filter(meal => meal.id != id) ;
                fetchMeals() ;
                closeModals();
                toastr.success('تم الحذف بنجاح') ;
            },
            error : function(){
                closeModals() ;
                toastr.error('حدث خطأ') ;
            }
        });

    }

    function closeModals() {
        $('.modal-overlay').hide();
    }
</script>
@endsection
