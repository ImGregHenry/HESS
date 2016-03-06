<?php

date_default_timezone_set('US/Eastern');
$DATE_FORMAT = "G:i:s";
$currentDateTime = DATE($DATE_FORMAT, TIME());

file_put_contents('/home/pi/TEST.txt', $currentDateTime . PHP_EOL);

?>
