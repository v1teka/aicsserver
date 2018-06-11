<!DOCTYPE html>
<html>
    <head>
        <title>Карта</title>
        <meta charset="utf-8" />
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
            
            function drawRoom(num) {
                var audience = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                audience.setAttributeNS(null, "d", "M100 100 L400 100 L400 660 L220 660 L220 600 L100 600 z"); //взять path из базы
                audience.setAttributeNS(null, "stroke-width", 2);
                audience.setAttributeNS(null, "stroke", "black");
                audience.setAttributeNS(null, "fill", "lightgray");
                document.querySelector("#canvas1").appendChild(audience);

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: 'getMap.php?number='+num
                }).done(function(data) {
                        drawInv(data);
                        $("#shutdownroom").bind("click", function(event){shutDownRoom(); return false;});
                        checkOnline();
                    });
            }
            
            function drawInv(data){
                $.each(data, function(i){
                    var invObject = createInventory(data[i].type, data[i].locationX, data[i].locationY);
                    
                    invObject.setAttributeNS(null, "title", data[i].name);
                    invObject.setAttributeNS(null, "inv", data[i].inventoryNumber);
                    invObject.setAttributeNS(null, "id", 'object' + i);
                    invObject.setAttributeNS(null, "ip", data[i].ip);
                    

                    $(invObject).bind("click", function(event){showInfo(this)});
                    if(data[i].type == 1) document.querySelector("#canvas2").appendChild(invObject);
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
                    monitorScreen.setAttributeNS(null, "fill", "paleturquoise");
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
                    url: "direct?ip="+elem.getAttribute("ip")+"&t=1"
                }).done(function(data) {
                    currentIP = elem.getAttribute("ip");
                    enableButtons();
                    $("#info").text(data);
                });
            }
            
            function showPrimaryInfo(elem){//исправить проверку онлайна
                $("#manage p").text(elem.getAttribute("class")+ ' ' + elem.getAttribute("title") + ' (' + elem.getAttribute("inv") + ')');
                if(elem.getAttribute('active')==1) $("#status").text("Онлайн");
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
                    url: "arp?ip="+e.getAttribute("ip")
                }).done(function(data){
                    if(data==1){
                        $(e).find(".screen").attr("fill", "paleturquoise");
                        return true;
                     }else{
                        $(e).find(".screen").attr("fill", "lightgrey");
                        return false;
                     }
                });
            }
            
            function checkOnline(){
                $('rect.pc').each(function(){
                    isOnline(this);
                });
                setTimeout(checkOnline, 30000);
            }

            function getinfo(ip){
                $.ajax({
                    url: "direct?ip="+ip+"&t=1"
                }).done(function(data) {
                    /*var asd = data.split('|');
                    asd.forEach(function callback(cur,ind,arr){
                        alert(cur);
                    });*/
                    $("#info").text(data);
                });
            }

            function getscreen(ip){
                $.ajax({
                    url: "direct?ip="+ip+"&t=2"
                }).done(function(data) {
                    document.getElementById("image").setAttribute( 'src',  "data:image/png;base64," + data);
                });
            }

            function shutdown(ip){
                console.log(ip);
                $.ajax({
                    url: "direct?ip="+ip+"&t=3"
                });
            }
            
            function shutDownRoom(){
                $('rect.pc').each(function(){
                    shutdown($(this).attr('ip'));
                });
            }

            function message(ip){
                $.ajax({
                    url: "direct?ip="+ip+"&t=4&message="+$("#messageinput").val()
                });
            }

            $(document).ready(function(){
                drawRoom("{{ $number }}");
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
