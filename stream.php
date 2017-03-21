<?php 
include 'init.php' ;
include 'cfg.php' ;
echo "msg system abc connected...\n\n";
echo "msg system abd loading history...\n\n";
echo "\n";
flush();
ob_flush();
echo "\n";
flush();
ob_flush();
$filename = "'".$TMP_PATH."chatroom".$room."'";
$handle = popen("touch ".$filename." && tail -f -n 10000 ".$filename." 2>&1", 'r');
echo "\n";
flush();
ob_flush();
echo "\n";
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
