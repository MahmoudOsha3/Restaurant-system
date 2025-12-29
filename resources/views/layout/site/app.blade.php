<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700;900&family=Reem+Kufi:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/site/style.css') }}">
    @yield('css')
</head>
<body>

    @include('layout.site.header')

    @yield('content')

    @include('layout.site.footer')

    <script src="{{ asset('js/site/app.js') }}"></script>
    @yield('js')
</body>
</html>
