<?php
/*************************************************
This page updates the database.

The page is accessed via the applescript or from
the web interface via one of the other php 
scripts (index.php or pagedetails.php)

If accessed from the web, after updating the 
database the user is redirected to the page they 
were on. 

Nothing gets displayed from this page, it just
does what's asked of it and redirects

*************************************************/

include_once('includes/config.php');
include_once('includes/functions.php');

//Assign all form fields to variables named as the form field name
foreach (array_keys($_REQUEST) as $key) { 
	$$key = $_REQUEST[$key]; 
	echo "$key is ${$key}<br />"; 
}

$who = $_COOKIE['user'];


//get referring url for the back button
$ref = getenv("HTTP_REFERER");


$link = dbconnect();

if ($a == "newproject") {
	//only update stuff that is set and not empty
	//this bit creates an array of strings for the query below
	$fields = array();
	if (isset($id) && $id != '') $fields[] =  "id = '" . mysql_real_escape_string($id) . "'";
	if (isset($project_name)) $fields[] =  "name = '" . mysql_real_escape_string($project_name) . "'";
	if (isset($client)) $fields[] =  "client = '" . mysql_real_escape_string($client) . "'";
	if (isset($brief)) $fields[] =  "brief = '" . mysql_real_escape_string($brief) . "'";
	if (isset($contact)) $fields[] =  "contact = '" . mysql_real_escape_string($contact) . "'";
	if (isset($porder)) $fields[] =  "porder = '" . mysql_real_escape_string($porder) . "'";
	
	if (count($fields) > 0 ) {
		$query = "insert into projects set " . implode(", ", $fields) . ", created_by='$who'";
		echo $query;
	}
	
	$result = @mysql_query($query);
	if(!$result){
	   echo('Error: ' . mysql_error());
	   exit();
	}else{
		$project_id = mysql_insert_id();
		
		$fields = array();
		$fields[] = "project_id = '".$project_id."'";
		if (isset($name)) $fields[] =  "tasks.name = '" . mysql_real_escape_string($name) . "'";
		if (isset($status)) $fields[] =  "tasks.status = '" . mysql_real_escape_string($status) . "'";
		if (isset($user_id)) $fields[] =  "tasks.user_id = '" . mysql_real_escape_string($user_id) . "'";
		if (isset($priority)) $fields[] =  "tasks.priority = '" . mysql_real_escape_string($priority) . "'";
		if (isset($deadline)) {
			if ($deadline != '') {
				$fields[] =  "tasks.deadline = '" . mysql_real_escape_string($deadline) . "'";
			}
		}
		if (isset($notes)) $fields[] =  "tasks.notes = '" . mysql_real_escape_string($notes) . "'";
		
		if (count($fields) > 0 ) {
			$query = "insert into tasks set " . implode(", ", $fields) . ", date_created = NOW(), created_by='$who'";
			echo $query;
		}
		
		$result = @mysql_query($query);
		if(!$result){
		   echo('Error: ' . mysql_error());
		   exit();
		}else{
			$task_id = mysql_insert_id();
			if ($user_id == '') {
				$user_id = 0;
			}
			$date = date('Y-m-d');
			
			$query = "insert into todo set user_id = $user_id, task_id = $task_id, date = '$date'";
			$result = @mysql_query($query);
			if(!$result){
			   echo('Error: ' . mysql_error());
			   exit();
			}else{
				header("location: projectdetails.php?id=$project_id&task_id=$task_id#task-$task_id");
			}
		}
	}	
} elseif ($a == "newtask") { //done
	$fields = array();
	$fields[] = "project_id = '".$project_id."'";
	if (isset($name)) $fields[] =  "tasks.name = '" . mysql_real_escape_string($name) . "'";
	if (isset($status)) $fields[] =  "tasks.status = '" . mysql_real_escape_string($status) . "'";
	if (isset($user_id)) $fields[] =  "tasks.user_id = '" . mysql_real_escape_string($user_id) . "'";
	if (isset($priority)) $fields[] =  "tasks.priority = '" . mysql_real_escape_string($priority) . "'";
	if (isset($deadline)) {
		if ($deadline != '') {
			$fields[] =  "tasks.deadline = '" . mysql_real_escape_string($deadline) . "'";
		}
	}
	if (isset($notes)) $fields[] =  "tasks.notes = '" . mysql_real_escape_string($notes) . "'";
	
	//this must be a new page
	if (count($fields) > 0 ) {
		$query = "insert into tasks set " . implode(", ", $fields) . ", date_created = NOW(), created_by='$who'";
		//echo $query;
	}
	
	$result = @mysql_query($query);
	if(!$result){
	   echo('Error: ' . mysql_error());
	   exit();
	}else{
		$task_id = mysql_insert_id();
		if ($user_id == '') {
			$user_id = 0;
		}
		$date = date('Y-m-d');
		
		$query = "insert into todo set user_id = $user_id, task_id = $task_id, date = '$date'";
		echo $query;
		$result = @mysql_query($query);
		if(!$result){
		   echo('Error: ' . mysql_error());
		   exit();
		}else{
			header("location: projectdetails.php?id=$project_id&task_id=$task_id#task-$task_id");
		}
	}
	
} elseif ($a == "editproject") { //done
	$name = mysql_real_escape_string($name);
	$client = mysql_real_escape_string($client);
	$brief = mysql_real_escape_string($brief);
	$contact = mysql_real_escape_string($contact);
	$porder = mysql_real_escape_string($porder);
	
	$query = "update projects set name='$name', brief='$brief', client='$client', contact='$contact', porder='$porder' where id=$id";
	echo $query;
	
	$result = @mysql_query($query);
	if(!$result){
	   echo('Error: ' . mysql_error());
	   exit();
	}else{
		header("location: $ref");	
	}

} elseif ($a == "edittask") { //done
	$fields = array();
	if (isset($name)) $fields[] =  "tasks.name = '" . mysql_real_escape_string($name) . "'";
	if (isset($status)) $fields[] =  "tasks.status = '" . mysql_real_escape_string($status) . "'";
	if (isset($user_id)) $fields[] =  "tasks.user_id = '" . mysql_real_escape_string($user_id) . "'";
	if (isset($deadline)) {
		if ($deadline != '') {
			$fields[] =  "tasks.deadline = '" . mysql_real_escape_string($deadline) . "'";
		}
	}
	if (isset($notes)) $fields[] =  "tasks.notes = '" . mysql_real_escape_string($notes) . "'";
	if (isset($priority)) $fields[] =  "tasks.priority = '" . mysql_real_escape_string($priority) . "'";
	
	if (count($fields) > 0 ) {
		$query = "update tasks set " . implode(", ", $fields) . ", date_updated = NOW() where id=$id";
		echo $query;
	}

	$result = @mysql_query($query);
	if(!$result){
	   echo('Error: ' . mysql_error());
	   exit();
	}else{
		//if its completed - update the date completed
		if ($status == 'Completed') {
			$query = "update tasks set date_completed = NOW() where id=$id";
			$result = @mysql_query($query);
			
			$query = "update todo set done = 1 where task_id = $id";
			$result = @mysql_query($query);
		} else {
			$query = "update todo set done = 0 where task_id = $id";
			$result = @mysql_query($query);
		}
		if (!strpos($ref, 'tasks.php')) {
			$ref = $ref . "&task_id=$id#task-$id";
		} 
		
		if ($user_id == '') {
			$user_id = 0;
		}
		$date = date('Y-m-d');
		
		$query = "update todo set user_id = $user_id where task_id = $id";
		$result = @mysql_query($query);
		if(!$result){
		   echo('Error: ' . mysql_error());
		   exit();
		}else{
			header("location: $ref");	
		}

	}		
} elseif ($a == 'archive') {

	$query = "update tasks set status = 'Archived', date_updated = NOW() where project_id=$id";
	$result = @mysql_query($query);
	if(!$result){
	   echo('Error: ' . mysql_error());
	   exit();
	}else{
		
		header("location: $ref");	
	}

} elseif ($a == 'addTime') {
	if ($minutes) {
		
		//check if a time log has already been added by this user today
		$query1 = "select * from log where user_id = $user_id and date = '$date' and task_id = $id";

		$result = @mysql_query($query1);
		if(!$result){
		   echo('Error: ' . mysql_error());
		   exit();
		}else{
			$count = mysql_num_rows($result); 
			if (!$count) {
				//no minutes have been added by this user today
				$query = "insert into log set task_id = $id, user_id = $user_id, date = '$date', minutes = $minutes";
			} else {
				//user has already added minutes today - add to total
				$row = mysql_fetch_assoc($result);
				$minutes = $minutes + $row['minutes'];
				$query = "update log set minutes = $minutes where user_id = $user_id and date = '$date' and task_id = $id";
			}
			
			//echo $query;
	
			$result = @mysql_query($query);
			if(!$result){
			   echo('Error: ' . mysql_error());
			   exit();
			}else{
				$ref = $ref . "&task_id=$id#task-$id";
				header("location: $ref");	
			}
		}
	} else {
		//no minutes where sent - send back
		header("location: $ref");		
	}
} elseif ($a == 'addBillable') {
	if ($amount) {
		
		$query = "insert into billable set project_id = $id, user_id = $user_id, amount = $amount, description = '$description'";
					
		echo $query;

		$result = @mysql_query($query);
		if(!$result){
		   echo('Error: ' . mysql_error());
		   exit();
		}else{
			//$ref = $ref . "&task_id=$id#task-$id";
			header("location: $ref");	
		}
	} else {
		//no minutes where sent - send back
		header("location: $ref");		
	}
}

dbclose($link);
?>