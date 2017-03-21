<?php 
include 'init.php' ;
include 'cfg.php' ;
$filename = "'".$TMP_PATH."chatroom".$room."'";
$handle = popen("touch ".$filename." && tail -f -n 10000 ".$filename." 2>&1", 'r');
echo "msg system abc connected\n";
echo "msg system abd loading history...\n";
flush();
ob_flush();
while(!feof($handle)) {
  $buffer = fgets($handle);
  echo "$buffer\n";
  flush();
  ob_flush();
}
pclose($handle);
?>
