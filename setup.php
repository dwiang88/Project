<?php

include_once('includes/config.php');
include_once('includes/functions.php');

//Assign all form fields to variables named as the form field name
foreach (array_keys($_REQUEST) as $key) { 
			$$key = $_REQUEST[$key]; 
			//echo "$key is ${$key}<br />"; 
}

//deals with form submission
if ($username) {
		//if a form has been submitted
		$id = checkPassword($username, $password);	
		
		if ($id) {
			$cookie_time = (3600 * 24 * 1000);
			//delete existing cookie
			setcookie ('user', '', time() - 3600);	
			//set new one
			setcookie ('user', $id, time() + $cookie_time);
				
			//redirect
			header("location:index.php");
		} else {
			$alert = "Incorrect Password";
		}

}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="eng" lang="eng"> 
	<head> 
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Setup - LGT</title>
		<meta http-equiv="Pragma" content="no-cache" />
		<meta http-equiv="Expires" content="-1" />
		<link href="css/styles.css" rel="stylesheet" type="text/css" />
		<meta name="robots" content="noindex" />
	</head> 
	
	<body> 
		<div class="loginlogo"> 

		</div> 
 		<div class='form'> 
 			
			<form method='post' action='#'> 
					<?php echo $alert; ?>		

				<ol> 
					<li> 
					<label for='username'>Username:</label> 
					<input type='text' name='username' maxlength='20' class='fields' /> 
					</li> 
					
					<li> 
					<label for='password'>Password:</label> 
					<input type='password' name='password' maxlength='20' class='fields' /> 
		
					</li> 
				</ol> 
								
				<input type='submit' value='Log In' class='right' /> 
			</form> 
		</div> 
		
	</body> 
</html>