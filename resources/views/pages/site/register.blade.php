@extends('layout.site.app')

@section('title', 'إنشاء حساب - شيخ المندي')

@section('css')
<style>
    .auth-page {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)),
                    url('https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=1200');
        background-size: cover;
        background-position: center;
        padding: 40px 20px;
    }

    .auth-card {
        background: rgba(21, 21, 21, 0.98);
        width: 100%;
        max-width: 600px; /* جعلناها أعرض قليلاً لتناسب البيانات الإضافية */
        padding: 40px;
        border-radius: 20px;
        border: 1px solid #333;
        box-shadow: 0 15px 35px rgba(0,0,0,0.5);
    }

    .auth-logo { font-size: 2.2rem; text-align: center; margin-bottom: 20px; font-family: 'Reem Kufi'; color: #fff; }
    .auth-logo span { color: var(--gold); }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr; /* تقسيم المدخلات لعمودين */
        gap: 20px;
    }

    .form-group { margin-bottom: 15px; text-align: right; }
    .form-group.full-width { grid-column: span 2; } /* العنوان يأخذ السطر كاملاً */

    .form-group label { display: block; color: #bbb; margin-bottom: 8px; font-size: 0.85rem; }

    .input-group { position: relative; }
    .input-group i { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: var(--gold); }

    .input-group input, .input-group textarea {
        width: 100%;
        padding: 12px 45px 12px 15px;
        background: #0a0a0a;
        border: 1px solid #333;
        border-radius: 10px;
        color: #fff;
        transition: 0.3s;
    }

    .input-group textarea { height: 80px; resize: none; }

    .auth-btn {
        width: 100%;
        padding: 14px;
        background: var(--gold);
        border: none;
        border-radius: 10px;
        color: #000;
        font-weight: bold;
        font-size: 1.1rem;
        cursor: pointer;
        margin-top: 20px;
    }

    .error-msg { color: #ff4d4d; font-size: 0.75rem; margin-top: 5px; }

    @media (max-width: 600px) {
        .form-grid { grid-template-columns: 1fr; }
        .form-group.full-width { grid-column: span 1; }
    }
</style>
@endsection

@section('content')
<br><br><br>
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-logo">شيخ <span>المندي</span></div>
        <h3 style="color: #fff; text-align: center; margin-bottom: 30px;">إنشاء حساب جديد</h3>

        <form action="{{ route('auth.store') }}" method="POST">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label>الاسم بالكامل</label>
                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="أدخل اسمك" required>
                    </div>
                    @error('name') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>رقم الهاتف</label>
                    <div class="input-group">
                        <i class="fas fa-phone"></i>
                        <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="01xxxxxxxxx" required>
                    </div>
                    @error('phone') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>البريد الإلكتروني</label>
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="mail@example.com" required>
                    </div>
                    @error('email') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>المنطقة / الحي</label>
                    <div class="input-group">
                        <i class="fas fa-map-marker-alt"></i>
                        <input type="text" name="city" value="{{ old('city') }}" placeholder="مثلاً: التجمع الخامس" required>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label>العنوان بالتفصيل (سيتم استخدامه لتوصيل الطلبات)</label>
                    <div class="input-group">
                        <i class="fas fa-home" style="top: 20px;"></i>
                        <textarea name="address" placeholder="اسم الشارع، رقم العمارة، رقم الشقة، علامة مميزة">{{ old('address') }}</textarea>
                    </div>
                    @error('address') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>كلمة المرور</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" placeholder="••••••••" required>
                    </div>
                    @error('password') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>تأكيد كلمة المرور</label>
                    <div class="input-group">
                        <i class="fas fa-check-double"></i>
                        <input type="password" name="password_confirmation" placeholder="••••••••" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="auth-btn">تأكيد التسجيل</button>
        </form>

        <p style="text-align: center; color: #888; margin-top: 20px;">
            لديك حساب بالفعل؟ <a href="{{ route('auth.login') }}" style="color: var(--gold); text-decoration: none;">سجل دخولك</a>
        </p>
    </div>
</div>
@endsection
