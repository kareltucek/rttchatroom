<html>
<head>
<?php

include 'init.php' ;
include 'cfg.php' ;

mkdir($TMP_PATH);
$myfile = fopen($TMP_PATH."chatid".$room, "r");
$phpcl = def(fread($myfile, 1), 1) % 5;
$myfile = fopen($TMP_PATH."chatid".$room, "w");
fwrite($myfile, $phpcl + 1);
?>
  <script>
var cl = <?php echo $phpcl;?>;

var d;
var id; 
var offset = 0;
var identity = 0;

function initId(){
  offset = offset + 1;
  d = new Date();
  id = cl + (d.getTime() + offset);
  document.getElementById('msg').value = '';
}


function submit(data) {
  //window.alert('submit called');
  var xmlHttp = new XMLHttpRequest();
  xmlHttp.open( "POST", "postmsg.php?room=<?php echo $room;?>", false ); // false for synchronous request
  xmlHttp.send( "msg " + "c" + cl + ' ' + id + ' ' + data.replace(/\n/g, '') );
}

function submitHandler(e) {
  if(e.keyCode == 32 || e.keyCode == 13 || <?php echo $REALTIME;?>)
    submit(document.getElementById("msg").value);
  if(e.keyCode == 13)
    initId();
}

function processRecord(cl, id, text) {
  var o = document.getElementById(id);
    var history = document.getElementById('history');
  if(o == null)
  {
    var mytext = document.createTextNode(text);
    var rec = document.createElement('p');
    rec.setAttribute('class', 'm '+cl);
    rec.setAttribute('id', id);
    rec.appendChild(mytext);
    history.appendChild(rec);
  }
  else
  {
    o.textContent = text;
  }
  history.scrollTop = history.scrollHeight;
  console.log('processed '+text);
}

function processLine(l) {
  var sep1 = l.indexOf(' ');
  var sep2 = l.indexOf(' ', sep1+1);
  var sep3 = l.indexOf(' ', sep2+1);
  var tpe = l.substring(0, sep1);
  var mcl = l.substring(sep1+1, sep2);
  var id = l.substring(sep2+1, sep3);
  var text = l.substring(sep3+1);
  if(tpe == 'msg')
  {
    processRecord(mcl, id, text);
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
    initId();
    submit("Hello");
    initId();
  }
}
  </script>
  <style>
.c0{background-color: #FFEEEE; padding:5px;}
.c1{background-color: #EEFFEE; padding:5px;}
.c2{background-color: #FFFFEE; padding: 5px;}
.c3{background-color: #FFEEEE; padding:5px;}
.c4{background-color: #EEEEFF; padding:5px;}
.m{ 
    border-style: solid;
    border-width: 1px;
    border-radius: 5px;
    border-color: silver;
    margin: 5px;
}
#history {height:80%; width:100%; overflow:auto;}
#msgbox {height:20%; width: 100%;}
p {padding:0px; margin:0px;}
textarea{width:100%; height:100%;}
  </style>
</head>
<body>
</body>
<div id="history">
connecting...
</div>
<div id="msgbox">
<textarea id="msg" onkeyup="submitHandler(event);" <?php echo "class=\"m c".$phpcl."\"";?> >

</textarea>
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
