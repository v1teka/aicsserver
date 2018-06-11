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
                audience.setAttributeNS(null, "d", "M50 50 L350 50 L350 750 L100 750 L100 700 L50 700 Z"); //взять path из базы
                audience.setAttributeNS(null, "stroke-width", 2);
                audience.setAttributeNS(null, "stroke", "black");
                audience.setAttributeNS(null, "fill", "lightgray");
                document.querySelector("svg").appendChild(audience);

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
                var i;
                $.each(data, function(i){
                    var invObject = createInventory(data[i].type, data[i].locationX, data[i].locationY);
                    
                    invObject.setAttributeNS(null, "title", data[i].name);
                    invObject.setAttributeNS(null, "inv", data[i].inventoryNumber);
                    invObject.setAttributeNS(null, "id", 'object' + i);
                    invObject.setAttributeNS(null, "ip", data[i].ip);
                    
                    if(data[i].active==1)   invObject.setAttributeNS(null, "fill", "blue");

                    $(invObject).bind("click", function(event){showInfo(this)});
                    document.querySelector("svg").appendChild(invObject);
                });
            }
            
            function createInventory(type, x, y){
                var newObject = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
                newObject.setAttributeNS(null, "stroke", "black");
                newObject.setAttributeNS(null, "fill", "white");
                var width=0;
                var height = 0;
                var typeName = "";
                
                switch(type){
                    case 1:
                    width = 30;
                    height = 30;
                    typeName = "pc";
                    newObject.setAttributeNS(null, "stroke", "black");
                    newObject.setAttributeNS(null, "fill", "darkblue");
                    newObject.setAttributeNS(null, "z-index", 3);
                    var monitorShape = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
                            monitorShape.setAttributeNS(null, "x", 2);
                            monitorShape.setAttributeNS(null, "y", 2);
                            monitorShape.setAttributeNS(null, "width", 40);
                            monitorShape.setAttributeNS(null, "height", 28);
                            monitorShape.setAttributeNS(null, "rx", 5);
                            monitorShape.setAttributeNS(null, "fill", "dimgray");
                            newObject.appendChild(monitorShape);
                    break;

                    case 2:
                    width = 60;
                    height = 40;
                    typeName = "table";
                    newObject.setAttributeNS(null, "z-index", 2);
                    newObject.setAttributeNS(null, "stroke", "black");
                    newObject.setAttributeNS(null, "fill-rule", "nonzero");
                    newObject.setAttributeNS(null, "fill", "sandybrown");
                    break;
                }
                newObject.setAttributeNS(null, "x", 100 + 60*(x-1));
                newObject.setAttributeNS(null, "y", 100 + 40*(y-1));
                newObject.setAttributeNS(null, "width", width);
                newObject.setAttributeNS(null, "height", height);
                newObject.setAttributeNS(null, "class", typeName);
                return newObject;
            }

            function showInfo(elem){
                showPrimaryInfo(elem);
                
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
            
            function showPrimaryInfo(elem){
                $("#manage p").text(elem.getAttribute("class")+ ' ' + elem.getAttribute("title") + ' (' + elem.getAttribute("inv") + ')');
                if(elem.getAttribute('fill')=='blue') $("#status").text("Онлайн");
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
                        e.setAttribute("fill", "blue");
                        return true;
                     }else{
                        e.setAttribute("fill", "white");
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
        <svg width="1000px" height="1000px" id="canvas" />
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
