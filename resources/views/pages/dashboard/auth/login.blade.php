<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول | نظام الإدارة الموحد</title>

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        :root {
            --admin-dark: #1a1a2e;
            --admin-blue: #0f3460;
            --accent: #e94560;
            --bg: #f4f7f6;
        }

        * {
            box-sizing: border-box;
            font-family: 'Cairo', sans-serif;
        }

        body {
            background-color: var(--bg);
            background-image:
                radial-gradient(circle at 0% 0%, rgba(26, 26, 46, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 100% 100%, rgba(233, 69, 96, 0.05) 0%, transparent 50%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 450px;
        }

        .login-card {
            background: white;
            padding: 50px 40px;
            border-radius: 25px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            border-top: 6px solid var(--accent);
        }

        .login-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .login-header i {
            font-size: 3rem;
            color: var(--admin-blue);
            margin-bottom: 15px;
        }

        .login-header h2 {
            color: var(--admin-dark);
            font-size: 1.8rem;
            margin: 0;
            font-weight: 700;
        }

        .login-header p {
            color: #888;
            margin-top: 10px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group i {
            position: absolute;
            right: 15px;
            top: 45px;
            color: #ccc;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: var(--admin-dark);
        }

        .form-control {
            width: 100%;
            padding: 14px 45px 14px 15px;
            border: 2px solid #f0f0f0;
            border-radius: 12px;
            font-size: 1rem;
            background: #fafafa;
        }

        .form-control:focus {
            border-color: var(--admin-blue);
            background: white;
            outline: none;
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            background: var(--admin-blue);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-login:hover {
            background: var(--admin-dark);
        }

        .error-message {
            background: #fff5f5;
            color: #c53030;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            border-right: 4px solid #c53030;
        }

        .footer-text {
            text-align: center;
            margin-top: 25px;
            color: #888;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-card">

        <div class="login-header">
            <i class="fas fa-user-shield"></i>
            <h2>شاشة تسجيل الدخول</h2>
            <p>سجل دخولك لإدارة النظام</p>
        </div>

        {{-- Errors --}}
        @if ($errors->any())
            <div class="error-message">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label>البريد الإلكتروني</label>
                <input
                    type="email"
                    name="email"
                    class="form-control"
                    value="{{ old('email') }}"
                    required
                >
                <i class="fas fa-envelope"></i>
            </div>

            <div class="form-group">
                <label>كلمة المرور</label>
                <input
                    type="password"
                    name="password"
                    class="form-control"
                    required
                >
                <i class="fas fa-lock"></i>
            </div>

            <button type="submit" class="btn-login">
                تسجيل الدخول
            </button>
        </form>

        <div class="footer-text">
            جميع الحقوق محفوظة &copy; {{ date('Y') }} - نظام الإدارة
        </div>

    </div>
</div>

</body>
</html>
