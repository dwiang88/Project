<?php

function dbconnect() {
	$link = @mysql_connect(DB_HOST, DB_USER, DB_PWD);
	if(!$link){
		echo ('Error connecting to the database: ' . mysql_error());
		exit ();
	}
	$db = @mysql_selectdb(DB_NAME);
	if(!$db){
	   echo('Error selecting database: ' . mysql_error());
	   exit();
	}
	
	return $link;
}

function dbclose($link) {
	mysql_close($link);
}

function parseTemplate($template, $inputs = array()) {
	if(file_exists($template)) {
		$contents = file_get_contents($template);
		$contents = str_replace('\\', '', $contents);
		
		foreach($inputs as $key => $value) {
			$contents = str_replace('{' . $key . '}', $value, $contents);
		}
		
		return $contents;
	}
	else {
		trigger_error('File "' . $template . '" does not exist');
	}
}

// Format Datetime Stamp Function
/*function formatDate($val, $format = NULL){
    list($date, $time) = explode(' ', $val);
    list($year, $month, $day) = explode('-', $date);
    list($hour, $minute, $second) = explode(':', $time);

	return date('d/m/y - h:ia', mktime((int)$hour, (int)$minute, (int)$second, (int)$month, (int)$day, (int)$year));
    return date('M d, Y h:i A', mktime((int)$hour, (int)$minute, (int)$second, (int)$month, (int)$day, (int)$year));

}*/

function formatDate($d, $format = NULL){
    $d = strtotime($d);
    
    return date('M d, Y', $d);
}

function isOverdue($d) {
	if (!$d) return false;
	
	$n = time();
	$ds = strtotime($d);
		
	if ($ds <= $n) {
		return true;
	} else {
		return false;
	}
}

//general db query
function querydb($query) {
	$link = dbconnect();
	$result = @mysql_query($query) or die(@mysql_error());
	if($result){
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
  			$result_array[] = $row;
 		}
		dbclose($link);
		return $result_array;
	}
}


//users
function getAllUsers() {
	$link = dbconnect();
	$query = "SELECT * FROM users";
	$result = @mysql_query($query) or die(@mysql_error());
	if($result){
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
  			$result_array[] = $row;
 		}
		dbclose($link);
		return $result_array;
	}
}

//check password
function checkPassword($user, $password) {
	$link = dbconnect();
	$query = "SELECT id FROM users where username = '$user' and password = '$password'";
	$result = @mysql_query($query) or die(@mysql_error());
	if(!$result){
		dbclose($link);
		return false;
	} else {
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
			dbclose($link);
  			return $row['id'];
 		}
	}
}


//get username
function getUsername($user_id) {
	$link = dbconnect();
	$query = "SELECT username FROM users where id = '$user_id'";
	$result = @mysql_query($query) or die(@mysql_error());
	if(!$result){
		dbclose($link);
		return false;
	} else {
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
			dbclose($link);
  			return $row['username'];
 		}
	}
}

//messages
function getMessages($user_id) {
	$link = dbconnect();
	$query = "SELECT * FROM messages WHERE sent_to = 0 OR sent_to = $user_id ORDER BY id DESC LIMIT 5 ";
	$result = @mysql_query($query) or die(@mysql_error());
	if($result){
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
  			$result_array[] = $row;
 		}
		dbclose($link);
		return $result_array;
	}
}

//new message
function newMessage($content, $sent_to, $user) {
	$link = dbconnect();
	$content = mysql_real_escape_string($content);
	$query = "insert into messages set content = '$content', sent_to = '$sent_to', sent_from = '$user' ";
	$result = @mysql_query($query) or die(@mysql_error());
	if($result){
		dbclose($link);
		return true;
	} else {
		dbclose($link);
		return false;
	}
}

//projects
function getProjects() {
	$link = dbconnect();
	$query = "SELECT * FROM projects WHERE id IN (SELECT project_id FROM tasks WHERE status != 'Completed') and id IN (SELECT project_id FROM tasks WHERE status != 'Archived')";
	$result = @mysql_query($query) or die(@mysql_error());
	if($result){
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
  			$result_array[] = $row;
 		}
		dbclose($link);
		return $result_array;
	}
}

function getMyProjects($user_id) {
	$link = dbconnect();
	//this query selects my projects where I have tasks that are not completed
	//$query = "SELECT * FROM projects WHERE id IN (SELECT project_id FROM tasks WHERE user_id = $user_id and status != 'Completed' and status != 'Archived')";
	
	//this query selects projects where I am involved but someones taks is incomplete (even if my task in the project is complete)
	$query = "SELECT * FROM projects WHERE id IN (SELECT project_id FROM tasks WHERE status != 'Completed') and id IN (SELECT project_id FROM tasks WHERE status != 'Archived') and id IN (SELECT project_id FROM tasks WHERE user_id = $user_id)";
	
	$result = @mysql_query($query) or die(@mysql_error());
	if($result){
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
  			$result_array[] = $row;
 		}
		dbclose($link);
		return $result_array;
	}
}

function getProject($id) {
	$link = dbconnect();
	$query = "SELECT * FROM projects where id = $id";
	$result = @mysql_query($query) or die(@mysql_error());
	if($result){
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
  			dbclose($link);
			return $row;
 		}
	}
}

function getCompletedProjects() {
	$link = dbconnect();
	$query = "SELECT * FROM projects WHERE id NOT IN (SELECT project_id FROM tasks WHERE status != 'Completed') and id IN (SELECT project_id FROM tasks WHERE status != 'Archived')";
	$result = @mysql_query($query) or die(@mysql_error());
	if($result){
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
  			$result_array[] = $row;
 		}
		dbclose($link);
		return $result_array;
	}
}

function isForBilling($id) {
	$link = dbconnect();
	$query = "SELECT * FROM tasks WHERE project_id = $id AND status != 'Completed'";
	//if there are any tasks in this project that are not completed then its not ready for billing
	$result = @mysql_query($query) or die(@mysql_error());
	if(mysql_num_rows($result) > 0){
		return false;
	} else {
		return true;
	}
	dbclose($link);

}

function getArchivedProjects() {
	$link = dbconnect();
	$query = "SELECT * FROM projects WHERE id NOT IN (SELECT project_id FROM tasks WHERE status != 'Archived')";
	$result = @mysql_query($query) or die(@mysql_error());
	if($result){
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
  			$result_array[] = $row;
 		}
		dbclose($link);
		return $result_array;
	}
}

//get project name
function getProjectName($id) {
	$link = dbconnect();
	$query = "SELECT client, name FROM projects where id = '$id'";
	$result = @mysql_query($query) or die(@mysql_error());
	if(!$result){
		dbclose($link);
		return false;
	} else {
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
			dbclose($link);
  			return $row['client'] . ' ' . $row['name'];
 		}
	}
}

//get client name
function getClientName($id) {
	$link = dbconnect();
	$query = "SELECT client FROM projects where id = '$id'";
	$result = @mysql_query($query) or die(@mysql_error());
	if(!$result){
		dbclose($link);
		return false;
	} else {
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
			dbclose($link);
  			return $row['client'];
 		}
	}
}

function getClient($id) {
	$link = dbconnect();
	$query = "SELECT * FROM clients where id = $id";
	$result = @mysql_query($query) or die(@mysql_error());
	if($result){
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
  			dbclose($link);
			return $row;
 		}
	}
}

//todos
function getTodos($user_id) {
	$link = dbconnect();
	$query = "SELECT * FROM todo WHERE user_id = $user_id";
	$result = @mysql_query($query) or die(@mysql_error());
	if($result){
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
  			$result_array[] = $row;
 		}
		dbclose($link);
		return $result_array;
	}
}
function getDaysTodos($user_id, $wc) {
	$link = dbconnect();
	$query = "SELECT * FROM todo WHERE user_id = $user_id and date = '$wc' ORDER BY sort_order";
	$result = @mysql_query($query) or die(@mysql_error());
	if($result){
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
  			$result_array[] = $row;
 		}
		dbclose($link);
		return $result_array;
	}
}
function addTodo($user_id, $name, $date) {
	$link = dbconnect();
	$query = "insert into todo set user_id = $user_id, name = '$name', date = '$date', type = 2, sort_order = 100";
	
	$result = @mysql_query($query);
	if(!$result){
	   echo('Error: ' . mysql_error());
	   exit();
	}else{
		dbclose($link);
		return true;
	}
}
function editTodo($user_id, $name, $date, $id) {
	$link = dbconnect();
	$query = "UPDATE todo set name = '$name', date = '$date' WHERE id = $id";
	
	$result = @mysql_query($query);
	if(!$result){
	   echo('Error: ' . mysql_error());
	   exit();
	}else{
		dbclose($link);
		return true;
	}
}

/*
function getDaysTodos($user_id, $wc) {
	$link = dbconnect();
	$workable = "'Briefed','In_Progress','Corrections_Required','Corrections_in_Progress'";
	$query = "SELECT * FROM tasks WHERE user_id = $user_id AND todo_date = '$wc' AND status IN ($workable) ORDER BY sort_order";
	$result = @mysql_query($query) or die(@mysql_error());
	if($result){
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
  			$result_array[] = $row;
 		}
		dbclose($link);
		return $result_array;
	}
}
*/

//task
function getTask($task_id) {
	$link = dbconnect();
	$query = "SELECT * FROM tasks WHERE id = $task_id";
	$result = @mysql_query($query) or die(@mysql_error());
	if($result){
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
  			$result_array[] = $row;
 		}
		dbclose($link);
		return $result_array;
	}
}



//project tasks
function getTasks($project_id) {
	$link = dbconnect();
	$query = "SELECT * FROM tasks WHERE project_id = $project_id ORDER BY sort_order";
	$result = @mysql_query($query) or die(@mysql_error());
	if($result){
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
  			$result_array[] = $row;
 		}
		dbclose($link);
		return $result_array;
	}
}

//mytasks
function getMyTasks($user_id) {
	$link = dbconnect();
	$query = "SELECT * FROM tasks WHERE user_id = $user_id AND status !='Completed' AND status !='Archived' ORDER BY priority ASC";
	$result = @mysql_query($query) or die(@mysql_error());
	if($result){
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
  			$result_array[] = $row;
 		}
		dbclose($link);
		return $result_array;
	}
}

//alltasks
function getAllTasks() {
	$link = dbconnect();
	//$query = "SELECT * FROM tasks WHERE status !='Completed' AND status !='Archived' ORDER BY priority";
	$query = "SELECT * FROM tasks WHERE project_id IN (SELECT id FROM projects WHERE id IN (SELECT project_id FROM tasks WHERE status != 'Completed') and id IN (SELECT project_id FROM tasks WHERE status != 'Archived'))";
	$result = @mysql_query($query) or die(@mysql_error());
	if($result){
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
  			$result_array[] = $row;
 		}
		dbclose($link);
		return $result_array;
	}
}

//clients
function getClients() {
	$link = dbconnect();
	$query = "SELECT * FROM clients";
	$result = @mysql_query($query) or die(@mysql_error());
	if($result){
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
  			$result_array[] = $row;
 		}
		dbclose($link);
		return $result_array;
	}
}
function findClientId($name) {
	$link = dbconnect();
	$query = "SELECT id FROM clients WHERE company_name = '$name'";
	$result = @mysql_query($query) or die(@mysql_error());
	if($result){
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
			dbclose($link);
			return $row['id'];
		}
	}
}
//client contacts
function getContacts($client_id) {
	$link = dbconnect();
	$query = "SELECT * FROM contacts WHERE client_id = $client_id ORDER BY name";
	$result = @mysql_query($query) or die(@mysql_error());
	if($result){
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
  			$result_array[] = $row;
 		}
		dbclose($link);
		return $result_array;
	}
}

//project proofs
function getProofs($project_id) {
	$link = dbconnect();
	$query = "SELECT * FROM proofs WHERE project_id = $project_id ORDER BY id DESC";
	$result = @mysql_query($query) or die(@mysql_error());
	if($result){
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
  			$result_array[] = $row;
 		}
		dbclose($link);
		return $result_array;
	}
}

//proofs versions
function getVersions($proof_id) {
	$link = dbconnect();
	$query = "SELECT * FROM proof_versions WHERE proof_id = $proof_id ORDER BY id DESC";
	$result = @mysql_query($query) or die(@mysql_error());
	if($result){
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
  			$result_array[] = $row;
 		}
		dbclose($link);
		return $result_array;
	}
}

//widgets
function getWidgets($user) {
	$link = dbconnect();
	$query = "SELECT * FROM widgets where user_id = $user ORDER by sort_order ASC";
	$result = @mysql_query($query) or die(@mysql_error());
	if($result){
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
  			$result_array[] = $row;
 		}
		dbclose($link);
		return $result_array;
	}
}


//save sticky
function saveSticky($content, $user) {
	$link = dbconnect();
	$query = "UPDATE widgets set content = '$content' where name = 'sticky' and user_id ='$user'";
	$result = @mysql_query($query) or die(@mysql_error());
	if($result){
		dbclose($link);
		return true;
	} else {
		dbclose($link);
		return false;
	}
}

//get log for task
function getLog($task_id) {
	$link = dbconnect();
	$query = "SELECT * FROM log WHERE task_id = $task_id ORDER BY date";
	$result = @mysql_query($query) or die(@mysql_error());
	if($result){
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
				$result_array[] = $row;
			}
		dbclose($link);
		return $result_array;
	}
}

//get total minutes - task
function taskMinutes($task_id) {
	$link = dbconnect();
	$query = "select SUM(minutes) as total_min from log where task_id = '$task_id'";
	$result = @mysql_query($query) or die(@mysql_error());
	if($result){
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
  			dbclose($link);
  			if ($row['total_min']) {
  				return $row['total_min'];
  			} else {
  				return 0;
  			}
 		}
	}
}

//get billables
function getBillables($project_id) {
	$link = dbconnect();
	$query = "SELECT * FROM billable WHERE project_id = $project_id";
	$result = @mysql_query($query) or die(@mysql_error());
	if($result){
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
				$result_array[] = $row;
			}
		dbclose($link);
		return $result_array;
	}
}

function billableAmount($project_id) {
	$link = dbconnect();
	$query = "select SUM(amount) as total_amount from billable where project_id = '$project_id'";
	$result = @mysql_query($query) or die(@mysql_error());
	if($result){
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
  			dbclose($link);
  			if ($row['total_amount']) {
  				return $row['total_amount'];
  			} else {
  				return 0;
  			}
 		}
	}
}


//get total minutes - project
function projectMinutes($project_id) {
	$link = dbconnect();
	$query = "select SUM(minutes) as total_min from log where task_id in (SELECT id FROM tasks WHERE project_id = $project_id)";
	$result = @mysql_query($query) or die(@mysql_error());
	if($result){
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
  			dbclose($link);
  			if ($row['total_min']) {
  				return $row['total_min'];
  			} else {
  				return 0;
  			}
 		}
	}
}





