<?php 
$tab = "tasks";

include_once('includes/config.php');
include_once('includes/functions.php');

if (!isset($_COOKIE['user'])) {
	//no cookie is found so redirect to setup.php
	header("location:setup.php");
}

$users = getAllUsers();

if (!isset($_GET['u'])) {
	$u = $_COOKIE['user'];
	if ($_COOKIE['user'] == 5 || $_COOKIE['user'] == 8) $u = 0;
} else {
	$u = $_GET['u'];
}

if ($u) { //$u is not 0 
	$userName = ucfirst(getUsername($u));
	$pageName = $userName . "&rsquo;s Tasks";
	$tasks = getMyTasks($u);
	$info = "All tasks assigned to  ".$userName.".";
} else { // $u is 0 - show all
	$pageName = "All Tasks";
	$tasks = getAllTasks();
	$info = "All tasks from currently live projects.";
}

//get header
require ('includes/header.php');
?>						
		<p class="options"><a id="colorSwitch" href="#">SWITCH COLOURS</a></p>
				
 		<?php require('includes/nav.php'); ?>

 		<div class="content">
 			
 			<h1><?php echo $pageName; ?></h1>
 			<p><?php echo $info; ?></p>
 			<form id="filter" action="" method="get">
 				<label for="u">Show: </label>
 				<select name="u" class="trigger" id="u">
 					<option value="0">-- All -- </option>
 					<?php foreach ($users as $user) { ?>
 						<option value="<?php echo $user['id']; ?>" <?php if ($user['id'] == $u) echo 'selected="selected"'; ?>><?php echo ucfirst($user['username']); ?></option>
 					<?php }  ?>
 				</select>
 			</form>
 			<table class="list" cellpadding="0" cellspacing="0" border="0" id="tasks-table">
 				<thead>
 					<tr>
 	 					<th></th>
 						<th>No.</th>
 						<th>Project</th>
	 					<th>Task</th>
	 					<?php if (!$_GET['my']) echo "<th>Assigned to</th>"; ?>
	 					<th>Status</th>
	 					<th>Deadline</th>
	 					<th></th>
 					</tr>
 				</thead>
 				<tbody>
 				<?php foreach ($tasks as $task) { 
 					
 					$assignedTo = getUsername($task['user_id']);
 				?>
 					<tr>
 						<td>
 							<?php 
 							$priority = $task['priority']; 
 							switch ($priority) {
 							    case '1. Urgent':
 							        echo '<img alt="'.$priority.'" src="images/urgent.png" />';
 							        break;
 							    case '2. High':
 							        echo '<img alt="'.$priority.'" src="images/high.png" />';
 							        break;
 						       	case '3. Medium':
 							        echo '<img alt="'.$priority.'" src="images/medium.png" />';
 							        break;
 							    case '4. Low':
 							        echo '<img alt="'.$priority.'" src="images/low.png" />';
 							        break;
 							}
 							?>
 						</td>
 						<td><?php echo $task['project_id']; ?></td>
 						<td><a href="projectdetails.php?id=<?php echo $task['project_id']; ?>"><?php echo getProjectName($task['project_id']); ?></a></td>
 						<td><a href="projectdetails.php?id=<?php echo $task['project_id']; ?>&task_id=<?php echo $task['id']; ?>#task-<?php echo $task['id']; ?>"><?php echo $task['name']; ?></a></td>
 						<?php if (!$_GET['my']) { 
 						 echo "<td>$assignedTo</td>";
 						 } ?>
 						<td>
 							<form method="post" action="update.php">
 								<input type="hidden" name="a" value="edittask" />
								<select name="status" id="status" class="trigger status <?php //echo $task['status']; ?>">
								<?php foreach($statuses as $status) { ?>
									<option <?php if ($status == $task['status']) echo 'selected="selected"'; ?> value="<?php echo $status; ?>"><?php echo $status; ?></option>
								<?php } ?>
								</select>
								<input type="hidden" name="id" value="<?php echo $task['id']; ?>"
 							</form>
 						</td>
 						<td <?php if (isOverdue($task['deadline'])) echo 'class="overdue"'; ?>><?php if($task['deadline']) echo $task['deadline']; else echo '<span></span>' ?></td>
 						<!--<td <?php if (isOverdue($task['deadline'])) echo 'class="overdue"'; ?>>
 						<?php if($task['deadline']) echo formatDate($task['deadline']); else echo 'Jan 01, 2200' ?></td>-->
 						<td><a href="projectdetails.php?id=<?php echo $task['project_id']; ?>&task_id=<?php echo $task['id']; ?>#task-<?php echo $task['id']; ?>"><img src="images/edit-small.png" border="0" /></a></td>
 					</tr>
				<?php } ?>
  				</tbody>
 			</table>
 			
 		</div>
		<script type="text/javascript" src="js/jquery.dataTables.min.js"></script> 
		<script type="text/javascript">
			$('#tasks-table').dataTable({
					"bJQueryUI": true,
					"sPaginationType": "full_numbers",
					"iDisplayLength" : 25,
					"aaSorting": [[ 6, "asc" ]],
					
			});
			//"aoColumns": [
			//				null,
			//				null,
			//				null,
			///				null,
			//				null,
			//				null,
			//				{ "sType": "date" },
			///				null
			//			]
			//"aaSorting": [[ 6, "asc" ]]
		</script>
	</body> 
</html>