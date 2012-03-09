<?php
$tab = "clients";

include_once('includes/config.php');
include_once('includes/functions.php');

if (!isset($_COOKIE['user'])) {
	//no cookie is found so redirect to setup.php
	header("location:setup.php");
}

$users = getAllUsers();

$pageName = "All Clients";
$clients = getClients();
$info = "";



//get header
require ('includes/header.php');
?> 
		
<div id="newclient" title="New Client">
	<form action="update.php" method="GET" class="addprojectform">
		<input type="hidden" name="a" value="newclient" />
		<fieldset>
			<h3>Client</h3>
			<div><label for="company_name">Company Name</label><input type="text" name="company_name" id="company_name" value="" class="text"/></div>
			<div><label for="address1">Address 1</label><input type="text" name="address1" id="address1" value="" class="text"/></div>
			<div><label for="address2">Address 2</label><input type="text" name="address2" id="address2" value="" class="text"/></div>
			<div><label for="town">Town</label><input type="text" name="town" id="town" value="" class="text"/></div>
			<div><label for="postcode">Postcode</label><input type="text" name="postcode" id="postcode" value="" class="text"/></div>
			<div><label for="telephone">Telephone</label><input type="text" name="telephone" id="telephone" value="" class="text"/></div>
		</fieldset>
		<input type="submit" name="submit" value="Add" />			
	</form>
</div>
				
		<p class="options"><a id="colorSwitch" href="#">SWITCH COLOURS</a></p>
				
 		<?php require('includes/nav.php'); ?>

 		<div class="content">
 			
 			<h1><?php echo $pageName; ?> <small><a href="#" id="addclient">New Client</a></small></h1>
 			<p><?php echo $info; ?></p>
  			<table class="list" cellpadding="0" cellspacing="0" border="0" id="clients-table">
 				<thead>
 					<tr>
 						<th>Company Name</th>
	 					<th>Telephone</th>
	 					<th>Website</th>
	 					<th></th>
 					</tr>
 				</thead>
 				<tbody>
 				<?php foreach ($clients as $client) { ?>
 					<tr>
 						<td><a title="<?php echo $client['company_name']; ?>" href="clientdetails.php?id=<?php echo $client['id']; ?>"><?php echo $client['company_name']; ?></a></td>
 						<td><?php echo $client['telephone']; ?></td>
 						<td><a href="<?php echo $client['website']; ?>"><?php echo $client['website']; ?></td>
 						<td><a href="clientdetails.php?id=<?php echo $client['id']; ?>"><img src="images/edit-small.png" border="0" /></a></td>
 					</tr>
				<?php } ?>
  				</tbody>
 			</table>

 		</div>
 		<script type="text/javascript" src="js/jquery.dataTables.min.js"></script> 
 		<script type="text/javascript">
 			$(function() {
	 			$('#clients-table').dataTable({
	 					"bJQueryUI": true,
	 					"sPaginationType": "full_numbers",
	 					"iDisplayLength" : 25,
	 					"aaSorting": [[ 0, "asc" ]]
	 			});

	 		});
 		</script>
	</body> 
</html>