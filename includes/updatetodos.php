<?php
echo "<pre>";
print_r($_GET);
echo "</pre>";

include_once 'config.php';
include_once 'functions.php';

$link = dbconnect();
	
foreach ($_GET as $item) {
	echo $item['name'];
	$query = "update todo set date = '" . $item['date'] . "', sort_order = ".$item['sort_order']." where id = '".$item['id']."'";
	echo $query;
	$result = @mysql_query($query) or die(@mysql_error());
	if($result){
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
  			$result_array[] = $row;
 		}
	}
}
dbclose($link);	

?>