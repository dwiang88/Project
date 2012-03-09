<?php 
$tab = "clients";

include_once('includes/config.php');
include_once('includes/functions.php');

if (!isset($_COOKIE['user'])) {
	//no cookie is found so redirect to setup.php
	header("location:setup.php");
}
$id = $_GET['id'];

$client = getClient($id);
$contacts = getContacts($id);

$date = date("Y-m-d");

$pageName = $client['company_name'];

//get header
require ('includes/header.php');

?>

<div id="newcontact" title="Add a Contact">
	<form action="update.php" method="get" name="addContact" class="addcontactform">
		<input type="hidden" name="client_id" value="<?php echo $id; ?>" />
		<input type="hidden" name="a" value="newcontact" />
		<div><label for="name">Name:</label><input type="text" name="name" id="name" value="" class="text"/></div>
		<div><label for="position">Position:</label><input type="text" name="position" id="position" value="" class="text"/></div>
		<div><label for="telephone">Telephone:</label><input type="text" name="telephone" id="telephone" value="" class="text"/></div>
		<div><label for="mobile">Mobile:</label><input type="text" name="mobile" id="mobile" value="" class="text"/></div>
		<div><label for="email">Email:</label><input type="text" name="email" id="email" value="" class="text"/></div>
		<div><label for="name">Notes:</label><textarea name="notes" id="notes" value=""></textarea></div>
		<div><input type="submit" name="submit" value="Add" /></div>
	</form>
</div>
	
		<p class="options"><a id="colorSwitch" href="#">SWITCH COLOURS</a></p>

 		<?php require('includes/nav.php'); ?>

 		<div class="content">
	 			<div class="col1">
	 				<form id="projectdetails" method="post" action="update.php">
	 					<input type="hidden" name="a" value="editclient" />
	 					<input type="hidden" name="id" value="<?php echo $id; ?>" />
	 					<input type="text" class="text stealth" name="company_name" id="company_name" value="<?php echo $client['company_name']; ?>">
		 				<label for="address">Address</label>
		 				<textarea class="text " name="address" id="address"><?php echo $client['address']; ?></textarea>
		 				<label for="telephone">Telephone</label>
		 				<input type="text" class="text " name="telephone" id="telephone" value="<?php echo $client['telephone']; ?>">
						<label for="fax">Fax</label>
						<input type="text" class="text " name="fax" id="fax" value="<?php echo $client['fax']; ?>">
						<label for="website">Website</label>
						<input type="text" class="text " name="website" id="website" value="<?php echo $client['website']; ?>">
						<label for="notes">Notes</label>
						<textarea class="text " name="notes" id="notes" ><?php echo $client['notes']; ?></textarea>
		 				<br class="clear" />
		 				<input type="submit" class="submit" value="Update Client Details" />
	 				</form>
	 			</div>
	 			<div class="col2">
	 				<div class="contacts">
	 					<h2 style="margin-top:0;">Contacts <small><a href="#" id="addContact">Add Contact</a></small></h2>
	 					<?php foreach ($contacts as $contact) { ?>
	 					<div class="businesscard">
	 						<form method="post" action="update.php">
	 							<input type="hidden" name="a" value="editcontact" />
	 							<input type="hidden" name="id" value="<?php echo $contact['id'] ?>" />
	 							<img src="images/profile.png" alt="" class="profile" />
	 							<input type="text" name="name" id="name" value="<?php echo $contact['name']; ?>" class="text stealth" style="font-weight:bold; font-size:18px" /><br/>
	 							<span style="font-size:11px;">Position:</span> <input type="text" name="position" id="position" value="<?php echo $contact['position']; ?>" class="text stealth" style="text-transform:uppercase;" /><br/><br/>
	 							<strong>T:</strong> <input type="text" name="telephone" id="telephone" value="<?php echo $contact['telephone']; ?>" class="text stealth"  /><br/>
	 							<strong>M:</strong> <input type="text" name="mobile" id="mobile" value="<?php echo $contact['mobile']; ?>" class="text stealth"  /><br/>
	 							<strong>E:</strong> <input type="text" name="email" id="email" value="<?php echo $contact['email']; ?>" class="text stealth" style="width:280px" />
	 							<a href="mailto:<?php echo $contact['email']; ?>"><img src="images/email_go.png" alt="send email" style="float:right; margin-left:4px" /></a>
	 							<a href="#" class="viewnotes"><img src="images/note.png" alt="view notes" style="float:right" /></a><br/>
	 							<textarea class="notes" name="notes" id="notes" style="width:95%; height: 100px; margin-top:10px"><?php echo $client['notes']; ?></textarea>
	 							<input type="submit" class="submit" value="Update Contact Details" />
	 						</form>
	 					</div>
	 					<?php } ?>
	 				</div>
	 				<script type="text/javascript">
	 					$(document).ready(function() {
	 						$('.businesscard input, .businesscard textarea').focus(function() {
	 							$(this).parent().find('.submit').fadeIn();
	 						});
	 						$('.viewnotes').click(function() {
	 							$(this).parent().find('.notes').fadeIn();
	 						});
	 					});
	 				</script>
	 			</div>
 			<br class="clear" />
 		</div>

			<script type="text/javascript"> 
				
			</script>
		
	</body> 
</html>