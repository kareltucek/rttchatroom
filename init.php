<?php
function def($a, $b){
  if($a == "")
    return $b;
  else
    return $a;
}

$room = def(preg_replace('/[^A-Za-z0-9]/', '', $_GET["room"]), 'default');
$name = def(preg_replace('/[^A-Za-z0-9]/', '', $_GET["name"]), 'John');
?>

