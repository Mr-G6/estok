<!DOCTYPE html>
<html>
<head>
    @yield('title')
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="/assets/lib/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/app.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="_token" content="{{csrf_token()}}">
</head>
<body>
@include('nav')

<div class="container">
    @yield('dashboard')
    @yield('warehouse')
    @yield('edit_warehouse')
    @yield('edit_product')
</div>

<script type="text/javascript" src="/assets/lib/jquery/dist/jquery.min.js"></script>
<script type="text/javascript" src="/assets/lib/bootstrap/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/assets/js/app.js"></script>
</body>
</html>
