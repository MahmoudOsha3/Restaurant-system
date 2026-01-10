@extends('layout.site.app')

@section('title', 'قائمة الطعام - شيخ المندي')

@section('css')
<style>
    :root { --gold: #ffd700; --dark-bg: #0a0a0a; --card-bg: #151515; }

    .all-menu-page { padding-top: 80px; background: var(--dark-bg); min-height: 100vh; color: white; direction: rtl; }

    /* الهيدر */
    .page-header {
        text-align: center; padding: 60px 20px;
        background: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)), url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=1200');
        background-size: cover; background-position: center; border-bottom: 2px solid var(--gold);
    }
    .page-header h1 { font-family: 'Reem Kufi', sans-serif; color: var(--gold); font-size: 3rem; }

    /* الحاوية الرئيسية */
    .menu-layout-container { display: flex; max-width: 1400px; margin: 0 auto; padding: 40px 20px; gap: 30px; }

    /* المنيو الجانبي */
    .side-menu-navigation { width: 260px; flex-shrink: 0; }
    .side-nav-inner { position: sticky; top: 100px; background: var(--card-bg); padding: 20px; border-radius: 20px; border: 1px solid #333; }
    .side-nav-inner h3 { color: var(--gold); margin-bottom: 20px; border-bottom: 1px solid #222; padding-bottom: 10px; font-family: 'Reem Kufi'; }

    .nav-category {
        display: flex; align-items: center; gap: 12px; padding: 12px 15px; color: #aaa;
        text-decoration: none; border-radius: 12px; margin-bottom: 8px; cursor: pointer; transition: 0.3s;
    }
    .nav-category:hover { background: #222; color: #fff; }
    .nav-category.active { background: var(--gold); color: #000; font-weight: bold; transform: translateX(-10px); }

    /* الكروت */
    .menu-sections-wrapper { flex: 1; }
    .category-title { font-family: 'Reem Kufi'; font-size: 2.2rem; margin-bottom: 30px; border-right: 5px solid var(--gold); padding-right: 15px; }

    .menu-cards-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px; }

    .food-card {
        background: var(--card-bg); border-radius: 20px; overflow: hidden; border: 1px solid #222;
        transition: 0.4s; animation: fadeInUp 0.5s ease forwards;
    }
    .food-card:hover { border-color: var(--gold); transform: translateY(-10px); }

    .img-container { width: 100%; height: 200px; overflow: hidden; }
    .img-container img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
    .food-card:hover img { transform: scale(1.1); }

    .card-info { padding: 20px; }
    .card-info h3 { color: var(--gold); font-size: 1.2rem; margin-bottom: 8px; }
    .card-info p { color: #888; font-size: 0.85rem; height: 40px; overflow: hidden; margin-bottom: 15px; }

    .card-footer { display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #222; padding-top: 15px; }
    .price { color: #fff; font-weight: bold; font-size: 1.2rem; }
    .old-price { color: #666; text-decoration: line-through; font-size: 0.8rem; margin-right: 5px; }

    .add-btn {
        background: var(--gold); border: none; width: 40px; height: 40px; border-radius: 12px;
        cursor: pointer; transition: 0.3s; color: #000; display: flex; align-items: center; justify-content: center;
    }
    .add-btn:hover { background: #fff; transform: rotate(90deg); }

    /* لودر التحميل */
    .loader { grid-column: 1/-1; text-align: center; padding: 100px; display: none; }
    .spinner { border: 4px solid rgba(255,215,0,0.1); border-left-color: var(--gold); border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; display: inline-block; }

    @keyframes spin { to { transform: rotate(360deg); } }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    /* ريسبونسيف الموبايل */
    @media (max-width: 768px) {
        .menu-layout-container { flex-direction: column; }
        .side-menu-navigation { width: 100%; position: sticky; top: 70px; z-index: 10; }
        .side-nav-inner ul { display: flex; overflow-x: auto; gap: 10px; padding-bottom: 10px; }
        .nav-category { white-space: nowrap; margin-bottom: 0; }
    }
</style>
@endsection

@section('content')
<main class="all-menu-page">
    {{-- <section class="page-header">
        <h1>القائمة الملكية</h1>
        <p>من فرن شيخ المندي إلى مائدتكم مباشرة</p>
    </section> --}}

    <div class="menu-layout-container">
        <aside class="side-menu-navigation">
            <div class="side-nav-inner">
                <h3>الأقسام</h3>
                <ul id="sideNav">
                    @foreach($categories as $cat)
                        <li>
                            <a class="nav-category" data-id="{{ $cat->id }}">
                                <i class="fas fa-utensils"></i> {{ $cat->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </aside>

        <div class="menu-sections-wrapper">
            <h2 class="category-title" id="catTitle">كل الأصناف</h2>
            <div class="menu-cards-grid" id="menuWrapper">
                <div class="loader" id="mainLoader"><span class="spinner"></span></div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuWrapper = document.getElementById('menuWrapper');
        const catTitle = document.getElementById('catTitle');
        const loader = document.getElementById('mainLoader');

        async function fetchProducts(categoryId = 'all') {
            // 1. تنظيف الحاوية وإظهار اللودر
            menuWrapper.querySelectorAll('.food-card, .no-data').forEach(el => el.remove());
            loader.style.display = 'block';

            try {
                const response = await fetch(`/api/category/${categoryId}`);

                const result = await response.json();

                const categories = result.data;

                loader.style.display = 'none';

                if (!categories || categories.length === 0) {
                    menuWrapper.insertAdjacentHTML('beforeend', '<div class="no-data" style="grid-column:1/-1; text-align:center; padding:50px;">لا توجد أقسام حالياً</div>');
                    return;
                }

                let hasMeals = false;

                // 3. اللوب على الأقسام والوجبات
                categories.forEach(category => {
                    if (category.meals && category.meals.length > 0) {
                        hasMeals = true;
                        category.meals.forEach(meal => {
                            const card = `
                                <div class="food-card">
                                    <div class="img-container">
                                        <img src="${meal.image_url}" alt="${meal.title}" onerror="this.src='https://via.placeholder.com/300x200?text=No+Image'">
                                    </div>
                                    <div class="card-info">
                                        <h3>${meal.title}</h3>
                                        <p>${meal.description || ''}</p>
                                        <div class="card-footer">
                                            <div class="price-box">
                                                <span class="price">${meal.price} <small>ج.م</small></span>
                                                ${meal.compare_price && meal.compare_price > meal.price ? `<del class="old-price">${meal.compare_price} ج.م</del>` : ''}
                                            </div>
                                            <button class="add-btn" onclick="addToCart(${meal.id})">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;
                            menuWrapper.insertAdjacentHTML('beforeend', card);
                        });
                    }
                });

                if (!hasMeals) {
                    menuWrapper.insertAdjacentHTML('beforeend', '<div class="no-data" style="grid-column:1/-1; text-align:center; padding:50px;">لا توجد وجبات في هذا القسم</div>');
                }

            } catch (error) {
                loader.style.display = 'none';
                console.error("Error:", error);
                menuWrapper.innerHTML = '<p class="no-data" style="color:red; text-align:center; grid-column:1/-1;">عذراً، فشل تحميل البيانات</p>';
            }
        }

        // تنصت على أزرار الأقسام
        document.querySelectorAll('.nav-category').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('.nav-category').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                catTitle.innerText = this.innerText;
                fetchProducts(this.dataset.id);
            });
        });

        // تحميل أولي
        fetchProducts('all');
    });
    let carts = [] ;

    $(document).ready(function(){
        fetchCarts() ;
    }) ;

    function addToCart(meal_id){
        $.ajax({
            url : "api/cart" ,
            method : "POST" ,
            headers : {'Accept' : 'application/json'} ,
            data : {
                user_id : `{{ auth()->user()->id ?? null }}` ,
                quantity : 1 ,
                meal_id : meal_id
            },
            success : function(){
                fetchCarts() ;
                toastr.success('تم إدخال العنصر الي السلة') ;

            },
            error: function(xhr){
                console.log(xhr.responseJSON?.message);
            }
        }) ;
    }

    function fetchCarts(){
        $.ajax({
            url :`carts` ,
            method : "GET" ,
            headers : {
                'Accept' : 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success : function(res){
                carts = res.data ;
                updateUI(carts) ;
            },
            error : function(){

            } ,
        }) ;
    }

    function updateUI() {
        const itemsCont = document.getElementById('cartItems');
        const cartTotal = document.getElementById('cartTotal');
        const cartCount = document.getElementById('cart-count');

        let total = 0;
        let count = 0;

        if (carts.length === 0) {
            itemsCont.innerHTML = '<p class="empty-msg">سلتك بانتظار أشهى المأكولات</p>';
        } else {

            itemsCont.innerHTML = '';
            carts.forEach(cart => {
                total += cart.meal.price * cart.quantity;
                count += cart.quantity ;

                itemsCont.innerHTML += `
                    <div class="cart-item">
                        <img src="${cart.meal.image_url}" alt="${cart.meal.title}" class="cart-item-img">
                        <div class="cart-item-info">
                            <h4>${cart.meal.title}</h4>
                            <p>${cart.meal.price} ج.م</p>
                        </div>
                        <div class="qty-controls">
                            <button onclick="changeQty(${cart.id}, -1)">-</button>
                            <span>${cart.quantity}</span>
                            <button onclick="changeQty(${cart.id}, 1)">+</button>
                        </div>
                    </div>
                `;
            });
        }

        // تحديث الأرقام النهائية
        cartTotal.innerText = total;
        cartCount.innerText = count;
    }

    function changeQty(id, amt) {
        const item = carts.find(cart => cart.id === id);
        if (!item) return;

        $.ajax({
            url:`api/cart/${id}`,
            method:'PUT' ,
            headers : {
                'Accept' : 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data : {
                quantity: amt ,
            } ,
            success : function(){
                fetchCarts() ;
            },
            error: function(xhr){
                alert(xhr.responseJSON?.message) ;
            }
        }) ;
        if(item.quantity <= 0) {
            cart = carts.filter(i => i.id !== id);
        }
        updateUI();
    }
</script>
@endsection
