<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ ($success)? 'Success' : 'Error'  }} | {{ config('app.name') }}</title>
    <style type="text/css">
        html, body, .container{
            height: 100%;
            width: 100%;
        }
        body{
            font-family: -apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;
            margin: 0;
        }
        .container{
            display: flex;
            text-align: center;
            align-items: center;
            justify-content: center;
        }
        .message > *{
            margin: 0;
        }
        .message > h1{
            font-size: 2rem;
            font-weight: 300;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="message">
            <h1>{{ ($success)? 'Success' : 'Error'  }}</h1>
            <p>You will be redirected shortly.</p>
        </div>
    </div>
</body>
</html>