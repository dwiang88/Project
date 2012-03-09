<?php
echo "<pre>";
print_r($_GET);
echo "</pre>";

include_once 'config.php';
include_once 'functions.php';

$link = dbconnect();
	
$query = "update todo set done = IF(done=1, 0, 1) where id = '".$_GET['id']."'";
$result = @mysql_query($query) or die(@mysql_error());
if($result){
	while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
			$result_array[] = $row;
		}
}

//also need to change status to completed here - but what would happen if it changed back?? Need to sort out statuses first.

dbclose($link);	

?>