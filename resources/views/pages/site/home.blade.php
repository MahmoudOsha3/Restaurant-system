@extends('layout.site.app')

@section('title' , 'شيخ المندي')
@section('css')
<style>
.menu-container {
    padding: 60px 0;
    overflow: hidden; /* يمنع السكرول الخارجي للموقع */
}

.horizontal-scroll-wrapper {
    width: 100%;
    overflow-x: auto; /* تفعيل السكرول الأفقي */
    padding: 30px 8%;
    scrollbar-width: none; /* إخفاء شريط السكرول لفايرفوكس */
}

.horizontal-scroll-wrapper::-webkit-scrollbar {
    display: none; /* إخفاء شريط السكرول لكروم */
}

.food-scroll-grid {
    display: flex;
    flex-wrap: nowrap; /* إجباري: عدم النزول لسطر جديد */
    gap: 30px;
}

.category-item {
    flex: 0 0 300px; /* إجباري: عرض ثابت للكرت (300px) */
    max-width: 300px;
    background: #1a1a1a;
    border-radius: 15px;
    border: 1px solid #333;
}

.category-item img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 15px 15px 0 0;
}
</style>

@endsection

@section('content')
        <section class="hero" id="home">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="animate-up">مذاق الأصالة العربية</h1>
            <p class="animate-up">نأخذك في رحلة إلى عالم النكهات المطهوة ببطء على الطريقة التقليدية</p>
            <div class="hero-btns animate-up">
                <a href="#menu" class="btn-main">استعرض القائمة <i class="fas fa-utensils"></i></a>
                <a href="#booking" class="btn-outline">حجز طاولة</a>
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
                        <div class="badge">مميز</div>
                        <img src="{{ $meal->image_url }}" alt="{{ $meal->title }}">

                        <div class="card-info">
                            <h3>{{ $meal->title }}</h3>
                            <div class="price-row">
                                <span class="price">{{ $meal->price }} ج.م</span>
                                <button class="add-btn" onclick="addToCart({{ $meal->id }}, '{{ $meal->title }}', {{ $meal->price }}), '{{ $meal->image_url }}'">+</button>
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
@endsection
