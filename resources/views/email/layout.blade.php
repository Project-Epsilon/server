<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">

    <style>
        html, body {
            background-color: #ff4a4a;
        }
        body {
            padding: 4rem 0;
        }
        .wrapper {
            background-color: white;
            margin: 0 auto;
            max-width: 680px;
        }
        .header{
            border-bottom: #eee solid 1px;
        }
        .header .logo{
            font-size: 1.4rem;
            font-weight: 300;
            padding: 1.3rem;
            margin: 0;
        }
        .main{
            background-color: #fdfdfd;
        }
        .title{
            padding: 1.4rem;
            text-align: center;
            font-size: 2rem;
            font-weight: 200;
        }
        .content{
            padding-bottom: 2rem;
        }
        .footer{
            border-top: #eee solid 1px;
        }
        .footer p{
            padding: 1.3rem 0;
            font-weight: 300;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid header text-center">
            <h2 class="logo">
                {{ config('app.name') }}
            </h2>
        </div>
        <div class="container-fluid main">
            <div class="title">
                @yield('title')
            </div>
            <div class="content">
                @yield('content')
            </div>
        </div>
        <div class="container-fluid text-center footer">
            <p>
                &copy {{ \Carbon\Carbon::now()->year }} {{ config('app.name') }}
            </p>
        </div>
    </div>
</body>
</html>