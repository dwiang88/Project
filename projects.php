<?php
$tab = "projects";

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
	$pageName = $userName . "&rsquo;s Projects";
	$projects = getMyProjects($u);
	$info = "All currently live projects that ".$userName." has tasks in.";
} else { // $u is 0 - show all
	$pageName = "All Projects";
	$projects = getProjects();
	$info = "All projects that have incomplete tasks.";
}


//get header
require ('includes/header.php');
?> 
		
<div id="newproject" title="New Project">
	<form action="update.php" method="GET" class="addprojectform">
		<input type="hidden" name="a" value="newproject" />
		<fieldset>
			<h3>Project</h3>
			<div><label for="id">Project ID:</label><input type="text" name="id" id="id" value="" class="text"/> (Leave blank to use next available)</div>
			<div><label for="client">Client</label><input type="text" name="client" id="client" value="" class="text"/></div>
			<div><label for="project_name">Project Name:</label><input type="text" name="project_name" id="project_name" value="" class="text"/></div>
			<div><label for="contact">Client Contact</label><input type="text" name="contact" id="contact" value="" class="text"/></div>
			<div><label for="porder">Purchase Order</label><input type="text" name="porder" id="porder" value="" class="text"/></div>
			<div><label for="brief">Brief:</label>
			<textarea name="brief" id="brief"></textarea></div>
		</fieldset>
		<fieldset>
			<h3>Create First Task</h3>
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
		</fieldset>
		<input type="submit" name="submit" value="Add" />			
	</form>
</div>
				
		<p class="options"><a id="colorSwitch" href="#">SWITCH COLOURS</a></p>
				
 		<?php require('includes/nav.php'); ?>

 		<div class="content">
 			
 			<h1><?php echo $pageName; ?> <small><a href="#" id="addproject">New Project</a></small></h1>
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
 			<table class="list" cellpadding="0" cellspacing="0" border="0" id="projects-table">
 				<thead>
 					<tr>
 						<th>No.</th>
	 					<th>Project</th>
	 					<th>Progress</th>
	 					<th></th>
 					</tr>
 				</thead>
 				<tbody>
 				<?php foreach ($projects as $project) {  	
 					$taskcount = querydb('SELECT COUNT(*) from tasks where project_id='.$project['id']);
 					$completedcount = querydb('SELECT COUNT(*) from tasks where status="Completed" and project_id='.$project['id']);
 					$percentComplete = round(($completedcount[0]['COUNT(*)'] / $taskcount[0]['COUNT(*)']) * 100);
 				?>
 					<tr>
 						<td><?php echo $project['id']; ?></td>
 						<td><a href="projectdetails.php?id=<?php echo $project['id']; ?>"><?php echo $project['client']; ?> <?php echo $project['name']; ?></a></td>
 						<td>
 							<div class="progress">
 								<div style="width:<?php echo $percentComplete ?>%"><?php echo $percentComplete; ?>%</div>
 							</div>
 							(<?php echo $completedcount[0]['COUNT(*)']; ?> of <?php echo $taskcount[0]['COUNT(*)']; ?> tasks completed)
 						</td>
 						<td><a href="projectdetails.php?id=<?php echo $project['id']; ?>"><img src="images/edit-small.png" border="0" /></a></td>
 					</tr>
				<?php } ?>
  				</tbody>
 			</table>

 		</div>
 		<script type="text/javascript" src="js/jquery.dataTables.min.js"></script> 
 		<script type="text/javascript">
 			$(function() {
	 			$('#projects-table').dataTable({
	 					"bJQueryUI": true,
	 					"sPaginationType": "full_numbers",
	 					"iDisplayLength" : 25,
	 					"aaSorting": [[ 0, "desc" ]]
	 			});
	 			//$("#client").autocomplete({
	 			//	source: "includes/getclients.php",
	 			//	options: {minLength:2}
	 			//});
	 			var availableTags = [
	 						"ActionScript",
	 						"AppleScript",
	 						"Asp",
	 						"BASIC",
	 						"C",
	 						"C++",
	 						"Clojure",
	 						"COBOL",
	 						"ColdFusion",
	 						"Erlang",
	 						"Fortran",
	 						"Groovy",
	 						"Haskell",
	 						"Java",
	 						"JavaScript",
	 						"Lisp",
	 						"Perl",
	 						"PHP",
	 						"Python",
	 						"Ruby",
	 						"Scala",
	 						"Scheme"
	 					];
	 					$( "#client" ).autocomplete({
	 						source: "includes/getclients.php"
	 					});
	 		});
 		</script>
	</body> 
</html>