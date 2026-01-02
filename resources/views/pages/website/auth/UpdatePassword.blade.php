@extends('layout.site.app')

@section('title', 'تعديل كلمة المرور')

@section('css')
<!-- نفس CSS صفحة الاستعادة -->
@endsection

@section('content')
<br><br><br>
<div class="auth-page">
    <div class="auth-card animate-up">
        <div class="auth-logo">شيخ <span>المندي</span></div>
        <h2 style="color: #fff; margin-bottom: 10px;">تغيير كلمة المرور</h2>
        <p style="color: #888; margin-bottom: 30px;">ادخل كلمة المرور الجديدة لتحديث حسابك</p>

        <form action="{{ route('password.update') }}" method="POST" class="auth-form">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <label>البريد الإلكتروني</label>
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="example@mail.com" value="{{ old('email') }}" required>
                </div>
                @error('email') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>كلمة المرور الجديدة</label>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="********" required>
                </div>
                @error('password') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>تأكيد كلمة المرور</label>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password_confirmation" placeholder="********" required>
                </div>
            </div>

            <button type="submit" class="auth-btn">تغيير كلمة المرور</button>
        </form>

        <div class="auth-footer">
            لديك حساب بالفعل؟ <a href="{{ route('auth.login') }}">تسجيل الدخول</a>
        </div>
    </div>
</div>
@endsection
