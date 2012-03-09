<?php 
$tab = "billing";
$pageName = "Completed Projects";
$info = "All completed projects ready for billing.";

include_once('includes/config.php');
include_once('includes/functions.php');

if (!isset($_COOKIE['user'])) {
	//no cookie is found so redirect to setup.php
	header("location:setup.php");
}

$projects = getCompletedProjects();
$users = getAllUsers();

//get header
require ('includes/header.php');
?>
		<p class="options"><a id="colorSwitch" href="#">SWITCH COLOURS</a></p>
		
 		<?php require('includes/nav.php'); ?>

 		<div class="content">
 			
 			<h1><?php echo $pageName; ?></h1>
 			<p><?php echo $info; ?></p>
 			<table class="list" cellpadding="0" cellspacing="0" border="0">
 				<thead>
 					<tr>
 						<th>Project No.</th>
	 					<th>Client</th>
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
 						<td><?php echo $project['client']; ?></td>
 						<td><?php echo $project['name']; ?></td>
 						<td>
 							<div class="progress">
 								<div style="width:<?php echo $percentComplete ?>%"><?php echo $percentComplete; ?>%</div>
 							</div>
 							(<?php echo $completedcount[0]['COUNT(*)']; ?> of <?php echo $taskcount[0]['COUNT(*)']; ?> tasks completed)
 						</td>
 						<td><a href="projectdetails.php?id=<?php echo $project['id']; ?>"><img src="images/edit-small.png" border="0" /></a><a href="update.php?a=archive&id=<?php echo $project['id']; ?>"><img src="images/done.png" border="0" /></a></td>
 					</tr>
				<?php } ?>
  				</tbody>
 			</table>

 		</div>
 		<script type="text/javascript" src="js/jquery.dataTables.min.js"></script> 
 		<script type="text/javascript">
 			$('.list').dataTable({
 					"bJQueryUI": true,
 					"sPaginationType": "full_numbers",
 					"iDisplayLength" : 25,
 					"aaSorting": [[ 0, "desc" ]]
 			});
 		</script>
	</body> 
</html>