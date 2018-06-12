<!DOCTYPE html>
<html>
    <head>
        <title>Карта</title>
        <meta charset="utf-8" />
        <meta name='csrf-token' content='{{ csrf_token() }}' />
        <style>
            #manage{
                position:fixed;
                left: 55%;
                top:10px;
                border: 1px solid black;
                background-color: #fffff1;
            }
        </style>
        <script type="text/javascript" src="/jquery-3.2.1.js"></script>
        <script>
            var currentIP = "";
            
            function drawRoom() {
                var audience = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                
                audience.setAttributeNS(null, "d", "{{ DB::table('classrooms')->select('walls')->where('title', $number)->pluck('walls')[0] }}");
                audience.setAttributeNS(null, "stroke-width", 2);
                audience.setAttributeNS(null, "stroke", "black");
                audience.setAttributeNS(null, "fill", "lightgray");
                document.querySelector("#canvas1").appendChild(audience);
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $("meta[name=\"csrf-token\"]").attr('content')
                    },
                    url: "getmap?room={{ $number }}"
                }).done(function(data) {
                        drawInv(data);
                        $("#shutdownroom").bind("click", function(event){shutDownRoom(); return false;});
                        checkOnline();
                    });
            }
            
            function drawInv(data){
                $.each(data, function(i){
                    var invObject = createInventory(data[i].type_id, data[i].x, data[i].y);
                    
                    //invObject.setAttributeNS(null, "title", " комп");
                    invObject.setAttributeNS(null, "inv", data[i].number);
                    invObject.setAttributeNS(null, "id", 'object' + i);
                    invObject.setAttributeNS(null, "address", data[i].mac);
                    if( data[i].state ) invObject.setAttributeNS(null, "online", "");
                    
                    if(data[i].active == 1) $(invObject).find(".screen").attr("fill", "paleturquoise");
                    $(invObject).bind("click", function(event){showInfo(this)});
                    if(data[i].type_id == 1) document.querySelector("#canvas2").appendChild(invObject);
                    else document.querySelector("#canvas1").appendChild(invObject);
                });
            }

            function createInventory(type, x, y){
                var newObject;
                
                switch(type){
                    case 1:
                    newObject = createPC(100 + 60*x - 50,100 + 40*(y-1)+2);
                    break;

                    case 2:
                    newObject = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
                    newObject.setAttributeNS(null, "width", 60);
                    newObject.setAttributeNS(null, "height", 40);
                    newObject.setAttributeNS(null, "x", 100 + 60*(x-1));
                    newObject.setAttributeNS(null, "y", 100 + 40*(y-1));
                    newObject.setAttributeNS(null, "class", "table");
                    newObject.setAttributeNS(null, "stroke", "black");
                    newObject.setAttributeNS(null, "fill-rule", "nonzero");
                    newObject.setAttributeNS(null, "fill", "sandybrown");
                    break;
                }
                
                return newObject;
            }

            function createPC(x,y){
                var newPC = document.createElementNS('http://www.w3.org/2000/svg', 'g');
                newPC.setAttributeNS(null, "class", "pc");

                var monitorShape = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
                monitorShape.setAttributeNS(null, "x", x);
                monitorShape.setAttributeNS(null, "y", y);
                monitorShape.setAttributeNS(null, "width", 40);
                monitorShape.setAttributeNS(null, "height", 28);
                monitorShape.setAttributeNS(null, "rx", 5);
                monitorShape.setAttributeNS(null, "fill", "dimgray");
                newPC.appendChild(monitorShape);

                var monitorScreen = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
                    monitorScreen.setAttributeNS(null, "x", x+3);
                    monitorScreen.setAttributeNS(null, "y", y+3);
                    monitorScreen.setAttributeNS(null, "width", 34);
                    monitorScreen.setAttributeNS(null, "height", 21);
                    monitorScreen.setAttributeNS(null, "fill", "black");
                    monitorScreen.setAttributeNS(null, "class", "screen");
                    newPC.appendChild(monitorScreen);

                    var monitorScreenColor = document.createElementNS('http://www.w3.org/2000/svg', 'polygon');
                    monitorScreenColor.setAttributeNS(null, "points", (x + 37) + ',' + (y+3) + ' ' + (x + 37) + ',' + (y+3+21) + ' ' + (x +3) + ',' + (y+3+21));
                    monitorScreenColor.setAttributeNS(null, "fill", "turquoise");
                    monitorScreenColor.setAttributeNS(null, "opacity", 0.4);
                    newPC.appendChild(monitorScreenColor);

                    var lowerStand = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
                    lowerStand.setAttributeNS(null, "x", x+12);
                    lowerStand.setAttributeNS(null, "y", y + 30);
                    lowerStand.setAttributeNS(null, "width", 16);
                    lowerStand.setAttributeNS(null, "height", 4);
                    lowerStand.setAttributeNS(null, "rx", 2);
                    lowerStand.setAttributeNS(null, "fill", "dimgray");
                    newPC.appendChild(lowerStand);        

                    var upperStand = document.createElementNS('http://www.w3.org/2000/svg', 'polygon');
                    upperStand.setAttributeNS(null, "points", (x+16) + ',' + (y+28) + ' ' + (x+24) + ',' + (y+28) + ' ' + (x+26) + ',' + (y+28+2) + ' ' + (x+14) + ',' + (y+2+28));
                    upperStand.setAttributeNS(null, "fill", "gray");
                    newPC.appendChild(upperStand);

                    var firstButton = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                    firstButton.setAttributeNS(null, "cx", x+6);
                    firstButton.setAttributeNS(null, "cy", y+26);
                    firstButton.setAttributeNS(null, "r", 1);
                    firstButton.setAttributeNS(null, "fill", "turquoise");
                    newPC.appendChild(firstButton);

                    var secondButton = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                    secondButton.setAttributeNS(null, "cx", x+9);
                    secondButton.setAttributeNS(null, "cy", y+26);
                    secondButton.setAttributeNS(null, "r", 1);
                    secondButton.setAttributeNS(null, "fill", "silver");
                    newPC.appendChild(secondButton);

                    var thirdButton = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                    thirdButton.setAttributeNS(null, "cx", x+12);
                    thirdButton.setAttributeNS(null, "cy", y+26);
                    thirdButton.setAttributeNS(null, "r", 1);
                    thirdButton.setAttributeNS(null, "fill", "silver");
                    newPC.appendChild(thirdButton);
                return newPC;
            }

            function showInfo(elem){
                showPrimaryInfo(elem);
                isOnline(elem);
                $("#image").attr("src", "");
                disableButtons();
                
                $.ajax({
                    url: "direct?address="+elem.getAttribute("address")+"&t=1"
                }).done(function(data) {
                    currentIP = elem.getAttribute("address");
                    enableButtons();
                    $("#info").text(data);
                });
            }
            
            function showPrimaryInfo(elem){
                $("#manage p").text(elem.getAttribute("class")+ /*' ' + elem.getAttribute("title") + */' (' + elem.getAttribute("inv") + ')');

                if(elem.getAttribute('online') != null) $("#status").text("Онлайн");
                else  $("#status").text("Оффлайн");
            }

            function disableButtons(){
                $("button").each(function( index, value ) {
                    value.setAttribute("disabled", 1);
                  });
            }

            function enableButtons(){
                $("button").each(function( index, value ) {
                    value.removeAttribute("disabled");
                  });
            }

            function isOnline(e){
                $.ajax({
                    url: "arp?address="+e.getAttribute("address")
                }).done(function(data){
                    if(data==1){
                        e.setAttribute("online", "");
                        $(e).find(".screen").attr("fill", "paleturquoise");
                        return true;
                     }else{
                        e.removeAttribute("online");
                        $(e).find(".screen").attr("fill", "black");
                        return false;
                     }
                });
            }
            
            function checkOnline(){
                $('.pc').each(function(){
                    isOnline(this);
                });
                setTimeout(checkOnline, 30000);
            }

            function getinfo(add){
                $.ajax({
                    url: "direct?address="+add+"&t=1"
                }).done(function(data) {
                    $("#info").text(data);
                });
            }

            function getscreen(add){
                $.ajax({
                    url: "direct?address="+add+"&t=2"
                }).done(function(data) {
                    document.getElementById("image").setAttribute( 'src',  "data:image/png;base64," + data);
                });
            }

            function shutdown(add){
                console.log(add);
                $.ajax({
                    url: "direct?address="+add+"&t=3"
                });
            }
            
            function shutDownRoom(){
                $('.pc').each(function(){
                    shutdown($(this).attr('address'));
                });
            }

            function message(add){
                $.ajax({
                    url: "direct?address="+add+"&t=4&message="+$("#messageinput").val()
                });
            }

            $(document).ready(function(){
                drawRoom();
            });
        </script>
    </head>
    <body>
        <h1>отрисовка кабинета {{$number}}</h1>
        <div>
            <a href="map">выбрать кабинет</a>
            <a id="shutdownroom" href="#">отключить все</a>
        </div>
        <svg style="position:absolute;" width="1000px" height="1000px" id="canvas1" />
        <svg style="position:absolute;" width="1000px" height="1000px" id="canvas2" />
        <div id="manage">
            <p></p>
            <span>Статус:</span><span id="status"></span>
            <button onClick="getscreen(currentIP)" disabled>Скриншот</button>
            <button onClick="shutdown(currentIP)" disabled>Выключить</button>
            <input id="messageinput" placeholder="Сообщение" />
            <button onClick="message(currentIP)" disabled>Сообщение</button>
            <img id="image" style="max-width: 600px"/>
        </div>
    </body>
</html>
