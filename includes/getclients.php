<?php
include_once('config.php');
include_once('functions.php');

$link = dbconnect();


if ($_GET['term']) {
	$term = $_GET['term'];
	$query = "SELECT company_name
	    FROM clients 
	    WHERE company_name LIKE '%$term%'
	    ";

	//echo json_encode($query);
	    
	$result=mysql_query($query) or die(mysql_error());
	    	
	$return = array();
	
	if ($result) {
		if (mysql_num_rows($result) > 0){
			while ($clients = mysql_fetch_array($result)) {
				$return[] = $clients['company_name'];
			}
		}
	}
	
	echo json_encode($return);
}	


?>