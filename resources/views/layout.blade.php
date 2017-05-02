<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="/assets/lib/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/lib/jquery-ui/themes/smoothness/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/lib/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/app.min.css">
    <meta name="_token" content="{{csrf_token()}}">
    @yield('title')
</head>
<body>
    @include('nav')
    <div class="container">
        @yield('content')
    </div>

    <script type="text/javascript" src="/assets/lib/jquery/dist/jquery.min.js"></script>
    <script src="/assets/lib/jquery-ui/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/assets/lib/bootstrap/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/assets/lib/moment/moment.js"></script>
    <script type="text/javascript" src="/assets/lib/bootbox.js/bootbox.js"></script>
    <script type="text/javascript" src="/assets/lib/mustache.js/mustache.js"></script>

    @if(env('APP_ENV') === "local")
        <script type="text/javascript" src="/assets/js/app.js"></script>
    @else
        <script type="text/javascript" src="/assets/js/app.min.js"></script>
    @endif</body>
</html>