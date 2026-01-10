@extends('layout.site.app')

@section('title' , 'شيخ المندي')
@section('css')
<link rel="stylesheet" href="{{ asset('css/site/home.css') }}">

@endsection

@section('content')
        <section class="hero" id="home">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="animate-up">مذاق الأصالة العربية</h1>
            <p class="animate-up">نأخذك في رحلة إلى عالم النكهات المطهوة ببطء على الطريقة التقليدية</p>
            <div class="hero-btns animate-up">
                <a href="{{ route('menu.index') }}" class="btn-main">استعرض القائمة <i class="fas fa-utensils"></i></a>
                <a href="#" class="btn-outline">حجز طاولة</a>
            </div>
        </div>
    </section>


    <section class="menu-container" id="id-menu">
        <div class="section-head">
            <span class="sub-title">اكتشف أطباقنا</span>
            <h2 class="main-title">القائمة الملكية</h2>
        </div>

        <div class="horizontal-scroll-wrapper">
            <div class="food-scroll-grid">
                @forelse ($meals as $meal)
                    <div class="food-card category-item animate-up" style="display: block !important;">
                        {{-- <div class="badge">مميز</div> --}}
                        <img src="{{ $meal->image_url }}" alt="{{ $meal->title }}">

                        <div class="card-info">
                            <h3>{{ $meal->title }}</h3>
                            <div class="price-row">
                                <span class="price">{{ $meal->price }} ج.م</span>
                                <button class="add-btn" onclick="storeToCart({{ $meal->id }})">+</button>
                            </div>
                        </div>
                    </div>
                @empty
                    <p style="color: white; text-align: center; width: 100%;">لا توجد وجبات متاحة حالياً.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section class="about-premium" id="about">
        <div class="about-grid">
            <div class="about-info">
                <span class="gold-text">من نحن</span>
                <h2>ثلاثون عاماً من الخبرة في فنون المندي</h2>
                <p>في "شيخ المندي"، لا نقدم مجرد طعام، بل نقدم إرثاً ثقافياً. ننتقي أفضل الذبائح البلدية ونستخدم حطب السمر الطبيعي لنضمن نكهة مدخنة لا تنسى.</p>
                <div class="stats">
                    <div class="stat-item"><h4>+15</h4><p>فرع</p></div>
                    <div class="stat-item"><h4>+100</h4><p>شيف محترف</p></div>
                    <div class="stat-item"><h4>+1M</h4><p>عميل سعيد</p></div>
                </div>
            </div>
            <div class="about-gallery">
                <div class="img-box box-1"><img src="https://images.unsplash.com/photo-1541544741938-0af808871cc0?w=500" alt=""></div>
                <div class="img-box box-2"><img src="https://images.unsplash.com/photo-1514362545857-3bc16c4c7d1b?w=500" alt=""></div>
            </div>
        </div>
    </section>
@endsection

@section('js')
<script>
    const slider = document.querySelector('.horizontal-scroll-wrapper');
    let isDown = false;
    let startX;
    let scrollLeft;

    slider.addEventListener('mousedown', (e) => {
        isDown = true;
        slider.classList.add('active');
        startX = e.pageX - slider.offsetLeft;
        scrollLeft = slider.scrollLeft;
    });
    slider.addEventListener('mouseleave', () => {
        isDown = false;
    });
    slider.addEventListener('mouseup', () => {
        isDown = false;
    });
    slider.addEventListener('mousemove', (e) => {
        if(!isDown) return;
        e.preventDefault();
        const x = e.pageX - slider.offsetLeft;
        const walk = (x - startX) * 2; // سرعة السحب
        slider.scrollLeft = scrollLeft - walk;
    });
</script>

<script>
    let carts = [] ;

    $(document).ready(function(){
        fetchCarts() ;
    }) ;

    function storeToCart(meal_id){
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
