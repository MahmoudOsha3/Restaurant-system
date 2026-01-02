<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700;900&family=Reem+Kufi:wght@500;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="{{ asset('css/site/style.css') }}">
    @yield('css')
</head>
<body>

    @include('layout.site.header')

    @yield('content')

    @include('layout.site.footer')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
            toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-left",
            timeOut: 3000,
            extendedTimeOut: 1000,
            newestOnTop: true,
            preventDuplicates: true,
            rtl: true,
            showMethod: "fadeIn",
            hideMethod: "fadeOut"
        };
    </script>
    <script src="{{ asset('js/site/app.js') }}"></script>
    @yield('js')
</body>
</html>
