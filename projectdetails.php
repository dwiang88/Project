<?php 
$tab = "projects";

include_once('includes/config.php');
include_once('includes/functions.php');

if (!isset($_COOKIE['user'])) {
	//no cookie is found so redirect to setup.php
	header("location:setup.php");
}

$project = getProject($_GET['id']);
$tasks = getTasks($_GET['id']);
$users = getAllUsers();

$proofs = getProofs($_GET['id']);

$client_id = findClientId($project['client']);


if ($proofs) {
	$indexlink = 'proofs/'.str_replace(' ','-',strtolower($project['client'])).'/'.$_GET['id'];
}


//echo "<pre>";
//print_r($tasks);
//echo "</pre>";


$date = date("Y-m-d");

$pageName = $project['id'] . ' - ' . $project['name'];

//get header
require ('includes/header.php');



?>

	
<div id="newtask" title="Add a task">
	<form action="update.php" method="get" name="newtask" class="addtaskform">
		<input type="hidden" name="project_id" value="<?php echo $_GET['id']; ?>" />
		<input type="hidden" name="a" value="newtask" />
		<div><label for="name">Task:</label><input type="text" name="name" id="name" value="" class="text"/></div>
		<div><label for="deadline">Deadline:</label><input type="text" id="deadline" name="deadline-ui" value="" class="text deadline-ui" /></div>
		<input type="hidden" name="deadline" id="deadline-alt" />
		<div><label for="user_id">Assign to:</label>
			<select id="user_id" name="user_id">
				<option value="">-</option>
				<?php foreach ($users as $user) { ?>
					<option value="<?php echo $user['id']; ?>"><?php echo $user['username']; ?></option>
				<?php }  ?>
			</select>
		</div>
		<div>
				<label for="priority">Priority:</label>
				<select id="priority" name="priority" class="filter">
			<?php foreach($priorities as $priority) { ?>
				<option <?php if ($priority == '3. Medium') echo 'selected="selected"'; ?> value="<?php echo $priority; ?>"><?php echo $priority; ?></option>
			<?php }	 ?>
				</select>
		</div><br/>
		<div>
			<label for="status">Status:</label>
			<select id="status" name="status" class="filter">
		<?php foreach($statuses as $status) { ?>
			<option value="<?php echo $status; ?>"><?php echo $status; ?></option>
		<?php } ?>
			</select>
		</div>
		<div><label for="notes">Notes:</label>
		<textarea name="notes" id="notes"></textarea></div>
		<div><input type="submit" name="submit" value="Add" /></div>
	</form>
</div>

<div id="newProof" title="Add a Proof">
	<form action="update.php" method="get" name="addProof" class="addProofform">
		<input type="hidden" name="project_id" value="<?php echo $_GET['id']; ?>" />
		<input type="hidden" name="client" value="<?php echo $project['client']; ?>" />
		<input type="hidden" name="a" value="addProof" />
		<div><label for="name">Name:</label><input type="text" name="name" id="name" value="" class="text"/></div>
		<div><input type="submit" name="submit" value="Add" /></div>
	</form>
</div>
		
		<p class="options"><a id="colorSwitch" href="#">SWITCH COLOURS</a></p>

 		<?php require('includes/nav.php'); ?>

 		<div class="content">
 			<form id="projectdetails" method="post" action="update.php">
 				<?php if($client_id || isForBilling($_GET['id']) || $indexlink) { ?>
 					<div class="triangle">&nbsp;</div>
 					<ul class="actions">
 						<?php if($client_id) { ?>
 							<li><a href="clientdetails.php?id=<?php echo $client_id; ?>" title="Client Details"><img src="images/addressbook.png" /></a></li>
 						<?php } ?>
 						<?php if ($indexlink) { ?>
 							<li><a href="<?=$indexlink?>" title="View Proofs"><img src="images/proof.png" border="0" /></a></li>
 						<?php } ?>
 						<?php if(isForBilling($_GET['id'])) { ?>
 							<li><a href="update.php?a=archive&id=<?php echo $_GET['id']; ?>" title="Mark as billed"><img src="images/done.png" border="0" /></a></li>
 						<?php } ?>
 					</ul>
 				<?php } ?>
 				
				<input type="hidden" name="a" value="editproject" />
				<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" />
				<input type="text" class="text stealth" name="client" id="client" value="<?php echo $project['client']; ?>">
	 			<input type="text" class="text stealth" name="name" id="name" value="<?php echo $project['name']; ?>">
	 			<div class="details">
	 				<ul>
	 					<li><strong>Job No.: </strong><?php echo $project['id']; ?></li>
		 					<li><strong>Created by: </strong><?php echo getUsername($project['created_by']); ?></li>
	 					<li><strong>Total Time: </strong><?php echo projectMinutes($_GET['id']); ?> mins</li>
	 					<li><strong>Total Billables: </strong>£<?php echo billableAmount($_GET['id']);  ?></li>
	 				</ul>
	 			</div>
	 			<div class="col1">
	 				<h2>Brief</h2>
	 				<textarea name="brief" id="brief"><?php echo $project['brief']; ?></textarea>
	 				<input type="submit" class="submit" value="Update Project Details" />
	 			</div>
	 			<div class="col2">
	 				<!--<label for="client">Client:</label>
	 				<input type="text" class="text stealth" id="client" name="client" value="<?php echo $project['client']; ?>" />-->
	 				<label for="contact">Client Contact:</label>
	 				<input type="text" class="text stealth" id="contact" name="contact" value="<?php echo $project['contact']; ?>" />
	 				<label for="porder">Purchase Order:</label>
	 				<input type="text" class="text stealth" id="porder" name="porder" value="<?php echo $project['porder']; ?>" />
	 				<label for="quoted">Quoted Price:</label>
	 				<input type="text" class="text stealth" id="quoted" name="quoted" value="<?php echo $project['quoted']; ?>" />
	 			</div>
 			</form>
 			<hr/>
 				<h4>Billable Items</h4>
				<?php $billables = getBillables($_GET['id']);
				if ($billables) { ?>
				<table width="100%" class="log">
					<thead>
						<th>Description</th>
						<th>Amount</th>
						<th>Added by</th>
					</thead>
					<tbody>
						<?php foreach ($billables as $billable) { ?>
							<tr>
								<td><?php echo $billable['description']; ?></td>
								<td>£<?php echo $billable['amount']; ?></td>
								<td><?php echo getUsername($billable['user_id']); ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
				<p>&nbsp;</p>
				<?php } ?>
				<form action="update.php" method="get" class="addBillable">
					<input type="hidden" name="a" value="addBillable" />
					<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" />

					<input type="hidden" name="user_id" value="<?php echo $_COOKIE['user']; ?>" />
					Add £<input type="text" name="amount" id="amount" /> 
					for <input type="text" name="description" id="description" /> <input type="submit" name="submit" value="Go" />
				</form>
 			<hr/>
 			<div class="clear">
 				<h2>Tasks <small><a href="#" id="addtask">Add Task</a></small></h2>
  				<ul id="tasks"> 
 				<?php foreach ($tasks as $task) { ?>
 					<li id="<?php echo $task['id']; ?>">
					<h3 id="task-<?php echo $task['id']; ?>"><a href="#<?php echo $task['id']; ?>"><?php echo $task['name']; ?>  - <span>Assigned to: <?php echo getUsername($task['user_id']); ?></span><span class="status <?php echo $task['status']; ?>"><?php echo $task['status']; ?></span></a></h3>
					<div class="task-content">
 						<div class="col1">
 							<form action="update.php" method="get" class="edittaskform">
 								<input type="hidden" name="a" value="edittask" />
 								<input type="hidden" name="id" value="<?php echo $task['id']; ?>" />
 								<label for="name">Task Name:</label>
 								<input type="text" class="text" name="name" id="name" value="<?php echo $task['name']; ?>" />
								<label for="user_id">Assigned to:</label>
								<select name="user_id" id="user_id">
									<option value="">-</option>
								<?php foreach ($users as $user) { ?>
									<option <?php if ($user['id'] == $task['user_id']) echo 'selected="selected"'; ?> value="<?php echo $user['id']; ?>"><?php echo $user['username']; ?></option>
								<?php }  ?>
								</select>
								<label for="status">Status:</label>
								<select name="status" id="status">
								<?php foreach($statuses as $status) { ?>
									<option <?php if ($status == $task['status']) echo 'selected="selected"'; ?> value="<?php echo $status; ?>"><?php echo $status; ?></option>
								<?php } ?>
								<?php if($task['status'] == 'Archived') { ?>
									<option selected="selected" value="Archived">Archived</option>
								<?php } ?>
								</select>
								<label for="priority">Priority:</label>
								<select name="priority" id="priority">
									<?php foreach($priorities as $priority) { ?>
								<option <?php if ($priority == $task['priority']) echo 'selected="selected"'; ?> value="<?php echo $priority; ?>"><?php echo $priority; ?></option>
								<?php }	 ?>
								</select>
								<label for="deadline-<?php echo $task['id']; ?>">Deadline:</label>
								<input class="deadline-ui" type="text" class="text" id="deadline-<?php echo $task['id']; ?>" name="deadline-ui" value="<?php echo $task['deadline']; ?>" />
								<input type="hidden" name="deadline" id="deadline-<?php echo $task['id']; ?>-alt" />
								<label for="notes">Notes:</label>
								<textarea name="notes" id="notes"><?php echo $task['notes']; ?></textarea>
								<input type="submit" name="submit" value="Update Task" />
							</form>
						</div>
						<div class="col2">
							<h4>Timesheet</h4>
							<p>Total Time for this task: <?php echo taskMinutes($task['id']);  ?> mins</p>
							<?php $logs = getLog($task['id']); 
							if ($logs) { ?>
							<table width="100%" class="log">
								<thead>
									<th>Date</th>
									<th>Minutes</th>
									<th>Added by</th>
								</thead>
								<tbody>
									<?php foreach ($logs as $log) { ?>
										<tr>
											<td><?php echo $log['date']; ?></td>
											<td><?php echo $log['minutes']; ?></td>
											<td><?php echo getUsername($log['user_id']); ?></td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
							<p>&nbsp;</p>
							<?php } ?>
							<form action="update.php" method="get" class="addTime">
								<input type="hidden" name="a" value="addTime" />
								<input type="hidden" name="id" value="<?php echo $task['id']; ?>" />
								
								<input class="date-ui" type="text" class="text" id="date-<?php echo $task['id']; ?>" name="date-ui" value="<?php echo date("d/m/Y"); ?>" />
								<input type="hidden" name="date" id="date-<?php echo $task['id']; ?>-alt" value="<?php echo date("Y-m-d"); ?>"/>
								
								<input type="hidden" name="user_id" value="<?php echo $_COOKIE['user']; ?>" />
								Add <input type="text" name="minutes" id="minutes" /> minutes <input type="submit" name="submit" value="Go" />
							</form>
						</div>
					</div>
					</li>
 				<?php } ?>
				</ul>
 			</div>
 			<div class="clear" id="proofs">
 				<h2>Proofs <small><a href="#" id="addproof">Add Proof</a></small></h2>
 				<?php foreach ($proofs as $proof) { ?>
 					<h3><a href="#"><?php echo $proof['name'] ?></a></h3>
 					<?php $versions = getVersions($proof['id']); ?>
 					<div class="proof-content">
 						<table class="proof-versions" width="100%">
 							<thead>
 								<tr>
 									<th>Name</th>
 									<th>URL</th>
 									<th>&nbsp;</th>
 								</tr>
 							</thead>
 							<tbody>
 								<?php foreach ($versions as $version) { ?>
 								<tr>
 									<td><?=$version['version_name']?></td>
 									<td><a href="<?=$version['url']?>"><?=$version['url']?></a></td>
 									<td></td>
 								</tr>
 								<?php } ?>
 							</tbody>
 						</table>
 						<form enctype="multipart/form-data" method="post" action="update.php">
							<input type="hidden" name="a" value="addVersion" />
							<input type="hidden" name="project_id" value="<?php echo $_GET['id']; ?>" />
							<input type="hidden" name="proof_id" value="<?=$proof['id']?>" />
							<input type="hidden" name="proof_name" value="<?=$proof['name']?>" />
							<input type="hidden" name="client" value="<?=$project['client']?>" />
 							<label for="version_name">Name (eg. v1):</label>
 							<input type="text" name="version_name" class="text" value="" />
 							<input type="file" name="file" value="" />
 							<input type="submit" name="submit" value="Upload Proof Version" />
 						</form>
 					</div>
 				<?php } ?>
 			</div>
 			<br class="clear" />
 		</div>

			<script type="text/javascript"> 
				function saveTaskOrder() {
					data = '';
					//build a query string
					$('#tasks li').each(function(i){
						//loop through all in this list
						data += '&task' + $(this).attr('id') + '[id]=' + $(this).attr('id');
						data += '&task' + $(this).attr('id') + '[sort_order]=' + i; 
					});
					//send query string to updatetodos.php
					$.get("includes/updatetaskorder.php", data);
				}
				
				$(document).ready(function() {
					<?php 
					if ($_GET['task_id']) {
						$task_id = $_GET['task_id'];
						?>
						$('#task-<?php echo $task_id; ?>').next().show();
						$('#task-<?php echo $task_id; ?>').addClass('open');
					<?php } ?>
					
					$('#tasks').sortable({ 
						start: function(event, ui) {
							wasDragged = true; 
						},
						update: function(event, ui) {
						    saveTaskOrder();
						}
					});
				});
			</script>
		
	</body> 
</html>