<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="eng" lang="eng"> 
	<head> 
		<meta http-equiv="X-UA-Compatible" content="IE=8,chrome=1">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>P.H.I.L. - <?php echo $pageName; ?></title>
		<meta http-equiv="Pragma" content="no-cache" />
		<meta http-equiv="Expires" content="-1" />
		<meta name="robots" content="noindex" />
		
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="viewport" content="initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, width=device-width">

		<link href="css/styles.css" rel="stylesheet" type="text/css">
		<link href="css/table_jui.css" rel="stylesheet" type="text/css">
		<link type="text/css" href="css/custom-theme/jquery-ui-1.8.1.custom.css" rel="stylesheet" />	

		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script> 
		<script type="text/javascript" src="js/jquery.cookie.js"></script>
		<script type="text/javascript" src="js/common.js"></script>
		
		

	</head>

	<body id="<?php echo $tab ?>" <?php if ($_COOKIE['theme'] == 'mono') echo 'class="mono"'; ?>> 	
		<div class="topbar">
			<form method="post" action="" id="global_s">
				<input type="search" name="search" value="Quick Search" id="global_search" />
			</form>
			<ul class="newtabs">
				<li><a href="index.php" <?php if ($tab == "home") echo 'class="selected"'; ?>>Dashboard</a></li>
				<li><a href="todo.php" <?php if ($tab == "todos") echo 'class="selected"'; ?>>To dos</a></li>
				<li><a href="projects.php" <?php if ($tab == "projects") echo 'class="selected"'; ?>>Projects</a>
					<ul>
						<li><a href="billing.php">For Billing</a></li>
						<li><a href="archive.php">Archive</a></li>
					</ul>
				</li>
				<li><a href="tasks.php" <?php if ($tab == "tasks") echo 'class="selected"'; ?>>Tasks</a></li>
				<li><a href="clients.php" <?php if ($tab == "clients") echo 'class="selected"'; ?>>Clients</a></li>
			</ul>
		</div>
		<script type="text/javascript">
			$("#global_search").focus(function(){ 
			    if($(this).val() == "Quick Search")
			    {
			      $(this).val('');
			    }
			  });
			  
			  $("#global_search").blur(function(){
			    if($(this).val() == '')
			    {
			      $(this).val("Quick Search");
			    } 
			  });
			  
			$("#global_search").autocomplete({
				source: "includes/global_search.php", 
				select: function(event, ui) {
					var id = ui.item.value.split(" ")[0];
					location.href = 'projectdetails.php?id=' + id;
				}
			});
			
		</script>
		<div class="loginlogo"> 
			&nbsp; 
		</div> 
		