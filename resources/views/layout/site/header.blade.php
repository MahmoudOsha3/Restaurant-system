    <div class="cart-sidebar" id="cartSidebar">
        <div class="cart-header">
            <h3>قائمة طلباتك</h3>
            <i class="fas fa-times close-cart" onclick="toggleCart()"></i>
        </div>
        <div class="cart-items" id="cartItems">
            <p class="empty-msg">سلتك بانتظار أشهى المأكولات</p>
        </div>
        <div class="cart-footer">
            <div class="total-price">الإجمالي: <span id="cartTotal">0</span> ج.م</div>
            <button class="checkout-btn">تأكيد الطلب الآن</button>
        </div>
    </div>

    <header class="navbar">
        <div class="logo">شيخ <span>المندي</span></div>
        <nav>
            <ul class="nav-links">
                <li><a href="#home">الرئيسية</a></li>
                <li><a href="#menu">القائمة الملكية</a></li>
                <li><a href="#about">حكايتنا</a></li>
                <li><a href="#booking">الحجز</a></li>
            </ul>
        </nav>
        <div class="header-actions">
            @if (auth()->check())
                <div class="user-dropdown">
                    <div class="user-trigger">
                        <i class="fas fa-user-circle"></i>
                        <span class="user-name">{{ auth()->user()->name }}</span>
                        <i class="fas fa-chevron-down arrow-icon"></i>
                    </div>
                    <div class="dropdown-content">
                        <a href="#"><i class="fas fa-history"></i> طلباتي</a>
                        <a href="{{ route('user.profile') }}"><i class="fas fa-cog"></i> ملفي الشخصي</a>
                        <hr>
                        <form action="{{ route('auth.logout') }}" method="POST" id="logout-form">
                            @csrf
                            <button type="submit" class="logout-btn-link">
                                <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="user-auth">
                    <a href="{{ route('auth.login') }}" class="login-btn"><i class="far fa-user"></i> دخول / تسجيل</a>
                </div>
            @endif

            <div class="cart-icon" onclick="toggleCart()">
                <i class="fas fa-shopping-basket"></i>
                <span id="cart-count">0</span>
            </div>
        </div>
    </header>
