@extends('layout.site.app')

@section('title', 'تسجيل الدخول - شيخ المندي')

@section('css')
<style>
    .auth-page {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)),
                    url('https://images.unsplash.com/photo-1534308983496-4fabb1a015ee?w=1200');
        background-size: cover;
        background-position: center;
        padding: 20px;
    }

    .auth-card {
        background: rgba(21, 21, 21, 0.95);
        width: 100%;
        max-width: 450px;
        padding: 40px;
        border-radius: 20px;
        border: 1px solid #333;
        box-shadow: 0 15px 35px rgba(0,0,0,0.5);
        backdrop-filter: blur(10px);
        text-align: center;
    }

    .auth-logo {
        font-size: 2.5rem;
        font-weight: bold;
        color: #fff;
        margin-bottom: 30px;
        font-family: 'Reem Kufi', sans-serif;
    }

    .auth-logo span { color: var(--gold); }

    .auth-form .form-group {
        margin-bottom: 20px;
        text-align: right;
    }

    .auth-form label {
        display: block;
        color: #bbb;
        margin-bottom: 8px;
        font-size: 0.9rem;
    }

    .input-group {
        position: relative;
    }

    .input-group i {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gold);
    }

    .auth-form input {
        width: 100%;
        padding: 12px 45px 12px 15px;
        background: #0a0a0a;
        border: 1px solid #333;
        border-radius: 10px;
        color: #fff;
        transition: 0.3s;
    }

    .auth-form input:focus {
        border-color: var(--gold);
        outline: none;
        box-shadow: 0 0 10px rgba(193, 155, 118, 0.2);
    }

    .auth-btn {
        width: 100%;
        padding: 12px;
        background: var(--gold);
        border: none;
        border-radius: 10px;
        color: #000;
        font-weight: bold;
        font-size: 1.1rem;
        cursor: pointer;
        transition: 0.3s;
        margin-top: 10px;
    }

    .auth-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(193, 155, 118, 0.4);
    }

    .auth-footer {
        margin-top: 25px;
        color: #888;
        font-size: 0.9rem;
    }

    .auth-footer a {
        color: var(--gold);
        text-decoration: none;
        font-weight: bold;
    }

    /* رسائل الخطأ من Laravel */
    .error-msg {
        color: #ff4d4d;
        font-size: 0.8rem;
        margin-top: 5px;
        display: block;
    }
    <style>
    /* الفاصل (Divider) */
    .divider {
        margin: 25px 0;
        position: relative;
        text-align: center;
    }
    .divider::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 0;
        width: 100%;
        height: 1px;
        background: #333;
        z-index: 1;
    }
    .divider span {
        background: #151515; /* نفس لون خلفية الكارت */
        padding: 0 15px;
        color: #888;
        font-size: 0.85rem;
        position: relative;
        z-index: 2;
    }

    /* حاوية الأزرار */
    .social-btns {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .social-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 12px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: bold;
        font-size: 0.95rem;
        transition: 0.3s;
        border: 1px solid transparent;
    }

    /* ألوان المنصات */
    .google { background: #fff; color: #000; }
    .google:hover { background: #f1f1f1; transform: translateY(-2px); }

    .facebook { background: #1877f2; color: #fff; }
    .facebook:hover { background: #166fe5; transform: translateY(-2px); }

    .github { background: #333; color: #fff; }
    .github:hover { background: #444; border-color: #555; transform: translateY(-2px); }

    .social-btn i { font-size: 1.2rem; }
</style>
</style>
@endsection

@section('content')
<br><br><br>
<div class="auth-page">
    <div class="auth-card animate-up">
        <div class="auth-logo">شيخ <span>المندي</span></div>
        <h2 style="color: #fff; margin-bottom: 10px;">مرحباً بك مجدداً</h2>
        <p style="color: #888; margin-bottom: 30px;">سجل دخولك لتتمكن من طلب أشهى الوجبات</p>

        <form action="#" method="POST" class="auth-form">
            @csrf

            <div class="form-group">
                <label>البريد الإلكتروني</label>
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="example@mail.com" required>
                </div>
                @error('email') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>كلمة المرور</label>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>
                @error('password') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div style="text-align: left; margin-bottom: 20px;">
                <a href="#" style="color: #888; font-size: 0.8rem; text-decoration: none;">نسيت كلمة المرور؟</a>
            </div>

            <button type="submit" class="auth-btn">دخول</button>
        </form>
        <div class="social-auth">
            <div class="divider">
                <span>أو سجل دخولك عبر</span>
            </div>

            <div class="social-btns">
                <a href="{{ route('socialite.login' , 'google') }}" class="social-btn google">
                    <i class="fab fa-google"></i>
                    <span>Google</span>
                </a>

                <a href="{{ route('socialite.login' , 'github') }}" class="social-btn github">
                    <i class="fab fa-github"></i>
                    <span>GitHub</span>
                </a>


                {{-- <a href="{{ url('auth/facebook') }}" class="social-btn facebook">
                    <i class="fab fa-facebook-f"></i>
                    <span>Facebook</span>
                </a> --}}
            </div>
        </div>

        <div class="auth-footer">
            ليس لديك حساب؟ <a href="{{ route('auth.create') }}">أنشئ حساباً جديداً</a>
        </div>
    </div>
</div>
@endsection
