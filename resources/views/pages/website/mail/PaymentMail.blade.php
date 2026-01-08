<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Confirm Order</title>
</head>
<body>
    <h1>Hi , {{ $user->name }}</h1>
    <p>You confirmed order number {{ $order }} and you paid
        {{ $order->total }} EGP ... 
    </p>
</body>
</html>

