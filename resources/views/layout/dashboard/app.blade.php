<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/dashboard/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    @yield('css')
</head>
<body>

<div id="app">

    @include('layout.dashboard.sidebar')

    @yield('content')


</div>

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

<script src="https://js.pusher.com/8.0/pusher.min.js"></script>

<script>
    Pusher.logToConsole = true;
    const pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
        cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
        forceTLS: false,
        enabledTransports: ['ws', 'wss'],
        authEndpoint: '/broadcasting/auth',
        auth: {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }
    });

    const channel = pusher.subscribe(
        'private-App.Models.Admin.{{ auth("admin")->id() }}'
    );

    channel.bind(
        'Illuminate\\Notifications\\Events\\BroadcastNotificationCreated',
        function (data) {
            console.log('Notification received:', data);
            

            toastr.success(
                data.message,
                data.title
            );
        }
    );
</script>


    </script>



@yield('js')

</body>
</html>
