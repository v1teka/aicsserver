<!DOCTYPE html>
<html>
    <head>
        <title>Карта</title>
    </head>
    <body>
        <h1>выбор кабинета</h1>
        @foreach (DB::table('classrooms')->select('title')->pluck('title') as $roomNumber)
            <a href="?room={{$roomNumber}}">{{ $roomNumber }}</a>
        @endforeach
    </body>
</html>
