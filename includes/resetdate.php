<?php

include_once('config.php');
include_once('functions.php');

$today = date("Y-m-d");

$link = dbconnect();
$today = date("Y-m-d");
$query = "UPDATE todo SET date = '$today' WHERE date < '$today' and done = 0";
echo $query . '<br/>';
$result = @mysql_query($query) or die(@mysql_error());
$num_rows = mysql_num_rows($result); 

dbclose($link);
