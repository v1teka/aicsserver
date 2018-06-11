<!DOCTYPE html>
<html>
    <head>
        <title>Список!</title>
        <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet" type="text/css">
        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                font-weight: 100;
                font-family: 'Ubuntu';
            }

            a{
                text-decoration: none;
                color: #2a5885;
                border-radius: 3px;
                padding: 10px 20px;
                font-size: 10px;
            }

            a:hover{
                background-color: #e9edf1;
            }

            li{
                margin: 25px;
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 45px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">Полный список</div>
                <ul>
                    @foreach($message as $one)
                        <li><a href="#" title="{{$one['room']}}">{{$one['description']}}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </body>
</html>
