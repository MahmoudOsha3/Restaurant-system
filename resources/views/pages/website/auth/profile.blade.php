@extends('layout.site.app')

@section('title', 'ملفي الشخصي - شيخ المندي')

@section('css')
<style>
    .profile-container {
        padding: 120px 20px 60px;
        background: #0a0a0a;
        min-height: 100vh;
    }
    .profile-card {
        max-width: 800px;
        margin: 0 auto;
        background: #151515;
        border-radius: 20px;
        border: 1px solid #333;
        overflow: hidden;
    }
    .profile-header {
        background: linear-gradient(45deg, #1a1a1a, #222);
        padding: 40px;
        text-align: center;
        border-bottom: 1px solid #333;
    }
    .user-avatar {
        width: 100px;
        height: 100px;
        background: var(--gold);
        border-radius: 50%;
        margin: 0 auto 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: #000;
    }
    .profile-body { padding: 40px; }

    .settings-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
    }
    .form-group { margin-bottom: 20px; text-align: right; }
    .form-group.full { grid-column: span 2; }

    .form-group label {
        display: block;
        color: var(--gold);
        margin-bottom: 10px;
        font-size: 0.9rem;
    }
    .form-group input, .form-group textarea {
        width: 100%;
        padding: 12px 15px;
        background: #0a0a0a;
        border: 1px solid #333;
        border-radius: 10px;
        color: #fff;
        transition: 0.3s;
    }
    .form-group input:focus { border-color: var(--gold); outline: none; }

    .save-btn {
        background: var(--gold);
        color: #000;
        padding: 15px 40px;
        border: none;
        border-radius: 10px;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
    }
    .save-btn:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(193, 155, 118, 0.3); }

    .alert { padding: 15px; border-radius: 10px; margin-bottom: 20px; text-align: center; }
    .alert-success { background: rgba(40, 167, 69, 0.2); color: #28a745; border: 1px solid #28a745; }
</style>
@endsection

@section('content')
<div class="profile-container">
    <div class="profile-card">
        <div class="profile-header">
            <div class="user-avatar">
                <i class="fas fa-user"></i>
            </div>
            <h2 style="color: #fff;">أهلاً بك، {{ auth()->user()->name }}</h2>
            <p style="color: #888;">يمكنك تعديل بياناتك الشخصية وعناوين الشحن من هنا</p>
        </div>

        <div class="profile-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('user.profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="settings-grid">
                    <div class="form-group">
                        <label>الاسم الكامل</label>
                        <input type="text" name="name" value="{{ auth()->user()->name }}" required>
                    </div>

                    <div class="form-group">
                        <label>البريد الإلكتروني</label>
                        <input type="email" name="email" value="{{ auth()->user()->email }}" required>
                    </div>

                    <div class="form-group">
                        <label>رقم الهاتف (للطلبات)</label>
                        <input type="tel" name="phone" value="{{ auth()->user()->phone ?? ''}}" placeholder="01xxxxxxxxx">
                    </div>

                    <div class="form-group">
                        <label>المدينة</label>
                        <input type="text" name="city" value="{{ auth()->user()->city ?? '' }}" placeholder="مثلاً: القاهرة">
                    </div>

                    <div class="form-group full">
                        <label>العنوان بالتفصيل</label>
                        <textarea name="address" rows="3">{{ auth()->user()->address ?? '' }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>كلمة مرور</label>
                        <input type="password" name="password" placeholder="••••••••">
                    </div>

                    <div class="form-group">
                        <label>تأكيد كلمة المرور</label>
                        <input type="password" name="password_confirmation" placeholder="••••••••">
                    </div>
                </div>

                <div style="text-align: left; margin-top: 20px;">
                    <button type="submit" class="save-btn">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
