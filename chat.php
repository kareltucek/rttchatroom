<html>
<head>
<?php

include 'init.php' ;
include 'cfg.php' ;

mkdir($TMP_PATH);
$myfile = fopen($TMP_PATH."chatid".$room, "r");
$phpcl = def(fread($myfile, 1), 1) % 7;
$myfile = fopen($TMP_PATH."chatid".$room, "w");
fwrite($myfile, $phpcl + 1);
?>
  <script>
var cl = <?php echo $phpcl;?>;

var d;
var id; 
var offset = 0;
var identity = 0;
var name = '<?php echo $name ;?>';
var handshakeId = '12345';
var handshaked = false;
var defaultFamily;

var sendByEnter = true;
var sendRealtime = true;

function initId(){
  offset = offset + 1;
  d = new Date();
  id = cl + (d.getTime() + offset);
  document.getElementById('area').value = '';
}


function submit(tpe, c, data) {
  var xmlHttp = new XMLHttpRequest();
  xmlHttp.open( "POST", "postmsg.php?room=<?php echo $room;?>", false ); // false for synchronous request
  xmlHttp.send( tpe + " " + c + ' ' + id + ' ' + data.replace(/\n/g, '<br>').replace(/ /g, '&nbsp') );
}

function msg(data) {
  submit('msg', 'c' + cl, '<b>' + name + ': </b>' + data);
}

function ping() {
  initId(); //these are important - otherwise we rewrite previous message!
  submit('ping', 'c' + cl, '<b>' + name + ' pings</b>');
  initId();
}

function report(data) {
  initId();
  var res = id;
  submit('status', 'c' + cl, '<b>' + data + '</b>');
  initId();
  return res;
}

function newColour()
{
  cl = (cl+1)%7;
  document.getElementById('area').setAttribute('class', 'm c' + cl);
  report(name + " changed his colour");
}

function submitHandler(e) {
  if(e == null || e.keyCode == 32 || e.keyCode == 13 || (sendRealtime && <?php echo $REALTIME;?>))
    msg(document.getElementById("area").value);
  if(e != null && e.keyCode == 13 && sendByEnter)
    initId();
}

function processRecord(cl, id, text) {
  var o = document.getElementById(id);
  var history = document.getElementById('history');
  if(o == null)
  {
    //var mytext = document.createTextNode(text);
    var rec = document.createElement('p');
    rec.setAttribute('class', 'm '+cl);
    rec.setAttribute('id', id);
    rec.innerHTML = text;
    //rec.appendChild(mytext);
    history.appendChild(rec);
  }
  else
  {
    o.innerHTML = text;
  }
  history.scrollTop = history.scrollHeight;
  //console.log('processed '+text);
}

function processLine(l) {
  var sep1 = l.indexOf(' ');
  var sep2 = l.indexOf(' ', sep1+1);
  var sep3 = l.indexOf(' ', sep2+1);
  var tpe = l.substring(0, sep1);
  var mcl = l.substring(sep1+1, sep2);
  var id = l.substring(sep2+1, sep3);
  var text = l.substring(sep3+1);
  processRecord(mcl, id, text);
  if(tpe == 'ping' && handshaked)
  {
    report('&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp' + name + ' pongs back!');
  }
  if(!handshaked && id == handshakeId)
  {
    handshaked = true;
  }

}

function process(text) {
  lines = text.split("\n");
  for(var i = 0; i < lines.length; i++)
    if(lines[i] != '')
      processLine(lines[i]);
  if(identity == 0)
  {
    identity = 1;
    //document.getElementById('connecting').outerHTML='';
    handshakeId = report(name +" has joined the room");
  }
}

function updateAttributes() {
  sendByEnter = document.getElementById('byenter').checked;
  sendRealtime = document.getElementById('realtime').checked;
}

function updateMonospace() {
  if(document.getElementById('monospace').checked)
  {
    defaultFamily = document.getElementById('body').style.fontFamily;
    document.getElementById('body').style.fontFamily = "Monospace";
  }
  else
  {
    document.getElementById('body').style.fontFamily = defaultFamily;
  }
}

function exitChat() {
  report(name +" has left the room");
}
  </script>
  <style>
body{
  height:100%;
  overflow:hidden;
  display: flex;
  flex-direction: column;
  font-family: initial;
}
.c0{background-color: #FFEEEE;}
.c1{background-color: #FFFFDD;}
.c2{background-color: #EEFFEE;}
.c3{background-color: #EEEEFF;}
.c4{background-color: #FFDDFF;}
.c5{background-color: #DDFFFF;}
.c6{background-color: #EEEEEE;}
.m{ 
    border-style: solid;
    border-width: 1px;
    border-radius: 5px;
    border-color: silver;
    margin: 5px;
    text-overflow: ellipsis;
    overflow:hidden;
    padding: 5px;
}
.r{float:right;}
#history {max-height:80%; width:100%; overflow:auto; flex-grow: 80;}
#msgbox { width: 100%; flex-grow:20; display:flex; flex-direction:column;}
#area{width:100%; max-height:100%; flex-grow:100;}
#controls {display:flex; flex-direction: row;}
p {padding:0px; margin:0px;}
  </style>
</head>
<body id='body' onunload='exitChat()'>
<div id="history">
<p id='connecting' class='m'> connecting...
</div>
<div id="msgbox">
<textarea id="area" onkeyup="submitHandler(event);" <?php echo "class=\"m c".$phpcl."\"";?> >
</textarea>
<div id='controls'>
monospace font: <input type='checkbox' id='monospace' onclick='updateMonospace();'> 
<?php
if($REALTIME){echo "realtime: <input type='checkbox' id='realtime' checked onclick='updateAttributes();'> ";}
?>
send by enter: <input type='checkbox' id='byenter' checked onclick='updateAttributes();'> 
<input type='submit'  onclick="ping(); " value='ping others'>
<input type='submit'  onclick="newColour();" value='new colour'>
<span style='flex-grow: 100;'>
<input type='submit' class='r' onclick="submitHandler(null); initId(); " value='send'>
</div>
</div>
  <script type="text/javascript">
  function xmlHttpRequest() {
    return (function (x,y,i) {
      if (x) return new x();
      for (i=0; i<y.length; y++) try { 
        return new ActiveXObject(y[i]);
      } catch (e) {}
    })(
      window.XMLHttpRequest, 
      ['Msxml2.XMLHTTP','Microsoft.XMLHTTP']
    );
  };
function stream(url) {
  // Declare the variables we'll be using
  var xmlHttp = xmlHttpRequest();
  xmlHttp.open("GET", url, true);
  var len = 0;
  xmlHttp.onreadystatechange = function() {
    if (xmlHttp.status == 200 && xmlHttp.readyState >=3) {
      var text = xmlHttp.responseText;
      text = text.substr(len, text.length-len);
      len = xmlHttp.responseText.length;
      //console.log(text);
      process(text);
    }
  }
  xmlHttp.send(null);
}           
stream('stream.php?room=<?php echo $room;?>');
</script>
</body>
</html>
