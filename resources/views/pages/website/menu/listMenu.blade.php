<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="{{asset('css/site/style.css')}}">
    <style>
        /* تنسيقات صفحة المنيو الكاملة */
.all-menu-page {
    padding-top: 100px; /* لترك مساحة للـ Nav الثابت */
}

.page-header {
    text-align: center;
    padding: 60px 0;
    background: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)), url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=1200');
    background-size: cover;
    background-position: center;
}

.page-header h1 { font-family: 'Reem Kufi'; color: var(--gold); font-size: 3.5rem; }

.menu-layout-container {
    display: flex;
    padding: 50px 8%;
    gap: 40px;
}

/* القائمة الجانبية الذكية */
.side-menu-navigation {
    width: 280px;
}

.side-nav-inner {
    position: sticky;
    top: 120px; /* المسافة من الأعلى عند الـ Scroll */
    background: #151515;
    padding: 25px;
    border-radius: 20px;
    border: 1px solid #333;
}

.side-nav-inner h3 {
    color: var(--gold);
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 1px solid #333;
}

.side-nav-inner ul { list-style: none; }

.side-nav-inner ul li a {
    color: #888;
    text-decoration: none;
    padding: 12px 15px;
    display: flex;
    align-items: center;
    gap: 12px;
    border-radius: 10px;
    margin-bottom: 8px;
    transition: 0.3s;
}

.side-nav-inner ul li a:hover,
.side-nav-inner ul li a.active {
    background: var(--gold);
    color: #000;
    font-weight: bold;
    transform: translateX(-5px);
}

/* محتوى المجموعات */
.menu-sections-wrapper {
    flex: 1;
}

.menu-category-block {
    margin-bottom: 60px;
    scroll-margin-top: 130px; /* مهم جداً للـ Scroll Spy */
}

.category-title {
    font-family: 'Reem Kufi';
    color: #fff;
    font-size: 2.2rem;
    margin-bottom: 30px;
    border-right: 5px solid var(--gold);
    padding-right: 15px;
}

.menu-cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 25px;
}

/* تعديلات الكروت لتناسب صفحة المنيو */
.food-card {
    background: #1a1a1a;
    border-radius: 15px;
    overflow: hidden;
    border: 1px solid #333;
    transition: 0.3s;
}

.food-card:hover { border-color: var(--gold); transform: translateY(-5px); }

.card-clickable { text-decoration: none; color: inherit; }

.food-card img { width: 100%; height: 200px; object-fit: cover; }

.card-info { padding: 20px; }

.card-info h3 { margin-bottom: 8px; color: var(--gold); }

.card-info p { font-size: 0.85rem; color: #aaa; height: 40px; }

.card-footer-action {
    padding: 0 20px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.price { font-weight: bold; font-size: 1.2rem; }
    </style>
</head>
<body>
<main class="all-menu-page animate-up">

    <section class="page-header">
        <h1>القائمة الكاملة</h1>
        <p>استكشف أشهى المأكولات المحضرة بعناية فائقة</p>
    </section>

    <div class="menu-layout-container">

        <aside class="side-menu-navigation">
            <div class="side-nav-inner">
                <h3>الأقسام</h3>
                <ul id="sideNav">
                    <li><a href="#mandi-sec" class="active"><i class="fas fa-drumstick-bite"></i> صواني المندي</a></li>
                    <li><a href="#grills-sec"><i class="fas fa-fire"></i> المشويات</a></li>
                    <li><a href="#pizza-sec"><i class="fas fa-pizza-slice"></i> بيتزا ومعجنات</a></li>
                    <li><a href="#appetizers-sec"><i class="fas fa-utensils"></i> المقبلات</a></li>
                    <li><a href="#drinks-sec"><i class="fas fa-glass-whiskey"></i> المشروبات</a></li>
                </ul>
            </div>
        </aside>

        <div class="menu-sections-wrapper">

            <section id="mandi-sec" class="menu-category-block">
                <h2 class="category-title">صواني المندي الملكية</h2>
                <div class="menu-cards-grid">
                    <div class="food-card">
                        <a href="product-details.html" class="card-clickable">
                            <img src="https://images.unsplash.com/photo-1544124499-58912cbddaad?w=500" alt="صينية">
                            <div class="card-info">
                                <h3>صينية العزيمة الكبرى</h3>
                                <p>نصف تيس بلدي مع أرز وخلطة خاصة</p>
                            </div>
                        </a>
                        <div class="card-footer-action">
                            <span class="price">1850 <small>ج.م</small></span>
                            <button class="add-btn" onclick="addToCart(1, 'صينية العزيمة', 1850)">+</button>
                        </div>
                    </div>
                    </div>
            </section>

            <section id="grills-sec" class="menu-category-block">
                <h2 class="category-title">المشويات على الفحم</h2>
                <div class="menu-cards-grid">
                    <div class="food-card">
                        <a href="product-details.html" class="card-clickable">
                            <img src="https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=500" alt="مشويات">
                            <div class="card-info">
                                <h3>كيلو كباب وكفتة</h3>
                                <p>لحم ضأن طازج بتتبيلة شيخ المندي</p>
                            </div>
                        </a>
                        <div class="card-footer-action">
                            <span class="price">550 <small>ج.م</small></span>
                            <button class="add-btn" onclick="addToCart(2, 'كباب وكفتة', 550)">+</button>
                        </div>
                    </div>
                </div>
            </section>

            </div>
    </div>
</main>
<script>
    window.addEventListener('scroll', () => {
    let current = "";
    const sections = document.querySelectorAll('.menu-category-block');
    const navLinks = document.querySelectorAll('.side-nav-inner ul li a');

    sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.clientHeight;
        // إذا كان المستخدم داخل حدود القسم
        if (pageYOffset >= (sectionTop - 160)) {
            current = section.getAttribute('id');
        }
    });

    navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href').includes(current)) {
            link.classList.add('active');
        }
    });
});

// إضافة تأثير النقر السلس (Smooth Scroll)
document.querySelectorAll('.side-nav-inner a').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});
</script>
</body>
</html>

