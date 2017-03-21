<?php 
include 'init.php' ;
include 'cfg.php' ;
$data = $HTTP_RAW_POST_DATA;
$myfile = fopen($TMP_PATH."chatroom".$room, "a");
fwrite($myfile, $data."\n\n");
?>
