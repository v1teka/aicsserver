<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title></title>
    <style type="text/css" media="all">
        .info_panel {
            background-color: rgba(255,255,255, .8);
            padding: 5px;
            font-size: 12px;
            font-family: Helvetica, Arial, sans-serif;
            position: absolute;
            border: 1px solid #333;
            color: #333;
            white-space: nowrap;
        }

    </style>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script>
        /*$(function() {
            $('.map rect').mouseover(function(e){
                alert('sd');
                                id = $(this).attr("id");

                                $('<div class="info_panel">'
                                        + 'Инвертарный номер: '
                                        + data[id].inventoryNumber
                                        + '<br>'
                                        + 'Процессор: '
                                        + data[id].cpu
                                        + '<br>' + '</div>')
                                        .appendTo('body');
                            })
                                    .mouseleave(function() { $('.info_panel').remove();
                                    })
                                    .mousemove(function(e){
                                        var mouseX = e.pageX,
                                                mouseY = e.pageY;

                                        $('.info_panel').css({top: mouseY-50, left: mouseX - ($('.info_panel').width()/2)});

                                    });
        });*/
        //$(function() {$('.map path').mouseover(function(){alert('sdsd');}  )});
         
        function updateMap() {
            $.ajax({
                    /*передача в формате JSON*/
                    type: 'POST',
                    dataType: 'json',
                    url: "{{ url('/map.php')}}", //данные для отображения пока хранятся в качестве php-массива
                    success: function (data) {
                        //создание контура аудитории
                        var audience116a = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                        audience116a.setAttributeNS(null, "d", "M50 50 L350 50 L350 750 L100 750 L100 700 L50 700 Z");
                        audience116a.setAttributeNS(null, "stroke-width", 2);
                        audience116a.setAttributeNS(null, "stroke", "black");
                        audience116a.setAttributeNS(null, "fill-opacity", 0);
                        document.querySelector("svg").innerHTML = "";
                        document.querySelector("svg").appendChild(audience116a);

                        //перебор компьютеров
                        var i;
                        for(i = 1; i < aud116a + 1; i++)
                        {
                            //компьютер должен быть в базе данных
                            if ((data['comp' + i].locationX))
                            {
                                //добавляемые компьютеры не должны находиться за пределами аудитории
                                if ( ((data['comp' + i].locationX !=1)||(data['comp' + i].locationY !=14))&&((data['comp' + i].locationX < 7)||(data['comp' + i].locationY < 15)) )
                                {
                                var comp = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
                                //аудиторию можно разбить на ячейки и представить их в качестве таблицы
                                //в ней 6 столбцов и 14 строк, при этом есть дверной вырез
                                //для отображения компьютера достаточно двух чисел: номера столбца и строки
                                var x = data['comp' + i].locationX;
                                var y = data['comp' + i].locationY;
                                comp.setAttributeNS(null, "x", 50*x);
                                comp.setAttributeNS(null, "y", 50*y);
                                comp.setAttributeNS(null, "width", 50);
                                comp.setAttributeNS(null, "height", 50);
                                comp.setAttributeNS(null, "id", 'comp' + i);
                                comp.setAttributeNS(null, "stroke", "black");
                                comp.setAttributeNS(null, "fill", "darkblue");
                                document.querySelector("svg").appendChild(comp);
                                }
                            }
                            else
                            {  
                                //функция удаления компьютера, который был удален из базы данных
                                //для этого в значении номера столбца должен стоять 0
                                var forRemove = document.getElementById('comp'+i);
                                document.querySelector("svg").removeChild(forRemove);
                            }

                        } //окончание цикла перебора компьютеров

                        //две нерабочие функции: вывод сообщения  с информацией и симуляция включения/выключения компьютеров
                        //работали ранее для статичной картинки
                        //после создания динамического изменения карты функции полетели
                        //исправление в процессе

                        /*
                        $('.comp rect').each(function(index, element) {
                            if(data.hasOwnProperty($(element).attr('id'))) {
                                if(data[$(element).attr('id')].active) {
                                    $(element).attr('class', 'active');
                                } else {
                                    $(element).attr('class', '');
                                }
                            }
                        });
                        */
                        $('#comp1').mouseover(function(){
                            alert('sd');
                        });
                        

                    } //конец вложенной функции AJAX
                }); //конец функции AJAX
        }
        
        var aud116a = 83;
        $(document).ready(function() {
            
            updateMap();
            setInterval(updateMap, 5000);
        });
        
    </script>
</head>
<body>
<style>
    rect.active{
        fill: yellow;
    }
</style>
<div class="map">
<svg width="1000px" height="1000px" id="canvas">
</svg>
</div>
</body>
</html>