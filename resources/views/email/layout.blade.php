<!DOCTYPE HTML>
<html>
    <head>
        <style>
            html, body{
                background-color: #2a88bd;
                margin: 0;
            }
            .container{
                max-width: 680px;
                margin: 60px auto 0 auto;
                background: #fff;
                border-radius: 5px;
                padding: 15px;
            }
            .text-center{
                text-align: center;
            }
            .btn{
                border: 0;
                border-radius: 5px;
                background-color: #2a88bd;
                color: white;
                padding: 10px 5px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header text-center">
                {{ config('app.name') }}
            </div>
            <div class="content">
                <div class="title">
                    @yield('title')
                </div>
                <div class="message">
                    @yield('message')
                </div>
            </div>
            <div class="footer text-center">
                &copy {{ config('app.name') }} {{ \Carbon\Carbon::now()->year }}
            </div>
        </div>
    </body>
</html>