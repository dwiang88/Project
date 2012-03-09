<?php
include_once('config.php');
include_once('functions.php');

$link = dbconnect();


if ($_GET['term']) {
	$term = $_GET['term'];
	$query = "SELECT id, client, name
	    FROM projects 
	    WHERE id LIKE '%$term%' OR client LIKE '%$term%' OR name LIKE '%$term%'
	    ";

	//echo json_encode($query);
	    
	$result=mysql_query($query) or die(mysql_error());
	    	
	$return = array();
	
	if ($result) {
		if (mysql_num_rows($result) > 0){
			while ($projects = mysql_fetch_array($result)) {
				$name = $projects['id'] . ' ' . $projects['client'] . ' ' .  $projects['name'];
				$url = 'projectdetails.php?id=' . $projects['id']; 
				$return[] = $name ;
			}
		}
	}
	
	echo json_encode($return);
}	


?>