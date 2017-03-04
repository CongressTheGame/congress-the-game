<?php
include 'connect.php';

$date=date('Y-m-d',time('Y-m-d'));
$next=date('Y-m-d',strtotime("+7 days",time('Y-m-d')));

mysql_query("update period set current=0 where startdate='$date'") or die(mysql_error());
mysql_query("update period set current=1 where startdate='$next'") or die(mysql_error());
?>