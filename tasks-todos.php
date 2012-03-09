<?php

include_once('includes/config.php');
include_once('includes/functions.php');

$tasks = getAllTasks();

foreach($tasks as $task):
	$user_id = $task[user_id];
	$task_id = $task[id];
	
	$link = dbconnect();
	$query = "INSERT INTO todo (user_id,task_id,date) VALUES ('$user_id','$task_id','2010-09-15')";
	$result = @mysql_query($query) or die(@mysql_error());
	if($result){
		echo $query . '<br/>';
	}
endforeach;