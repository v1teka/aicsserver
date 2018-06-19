<!DOCTYPE html>
<html>
    <head>
        <title>Карта</title>
    </head>
    <body>
        <h1>Выбирите кабинет:</h1>
        @foreach (DB::table('classrooms')->select('title')->pluck('title') as $roomNumber)
            <p style="font-size: 14pt; line-height: 0.3";><a href="?room={{$roomNumber}}">Кабинет №{{ $roomNumber }}</a></p>
        @endforeach
    </body>
</html>
