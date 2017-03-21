<?php 
include 'init.php' ;
include 'cfg.php' ;
$filename = "'".$TMP_PATH."chatroom".$room."'";
flush();
$handle = popen("touch ".$filename." && tail -f ".$filename." 2>&1", 'r');
while(!feof($handle)) {
  $buffer = fgets($handle);
  echo "$buffer\n";
  flush();
  ob_flush();
}
pclose($handle);
?>
