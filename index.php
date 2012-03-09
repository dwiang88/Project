<?php 
$tab = "home";
$pageName = "Dashboard";
include_once 'includes/config.php';
include_once 'includes/functions.php';
require_once('includes/simplepie.inc');

if (!isset($_COOKIE['user'])) {
	//no cookie is found so redirect to setup.php
	header("location:setup.php");
}

if (isset($_POST['stickynote'])) {
	//save note
	if (saveSticky($_POST['stickynote'], $_COOKIE['user'])) {
		$alert = "Note Saved!";
	}
}

if (isset($_POST['content'])) {
	//add message
	if (newMessage($_POST['content'], $_POST['sent_to'], $_COOKIE['user'])) {
		$alertM = "Message Sent!";
	}	
}

$widgets = getWidgets($_COOKIE['user']);
$users = getAllUsers();

//echo "<pre>";
//print_r($widgets);
//echo "</pre>";

//build columns
$col1 = '';
$col2 = '';
$col3 = '';

foreach ($widgets as $widget) {
	$output = '';
	$header = '<li class="widget" id="widget-'.$widget['id'].'" title="'.$widget['id'].'">';
	if ($widget['link'] == '') {
		$header .= '<div class="widget-head"><h3>'.$widget['title'].'</h3></div><div class="widget-content">';
	} else {
		$header .= '<div class="widget-head"><h3><a href="'.$widget['link'].'">'.$widget['title'].'</a></h3></div><div class="widget-content">';
	}
	

 	if ($widget['type']=='rss') {
 		$feed = $widget['content'];
 		
 		$rss = new SimplePie();
 		$rss->set_feed_url($feed, $_SERVER['DOCUMENT_ROOT'] . '/cache');
 		$rss->init();
 		$rss->handle_content_type();
 		
 		$output .= $header;
		if ($widget['name']=='delicious') {
			$output .= '<form method="get" action="http://delicious.com/search">
						<input type="hidden" name="context" value="userposts|limegreentangerine" />
						<input type="text"   name="p" size="32"
						 maxlength="255" value="" />
						<input type="submit" value="Go" />
						</form>';
		}
		$output .= "<ul>";
		echo $rss->error();
		foreach ($rss->get_items(0, 10) as $item) {
			$link = $item->get_permalink();
			$title = $item->get_title();
			$output .= "<li><a href=\"".$link."\">".$title."</a></li>";
		}
		$output .= "</ul>";
		
		
 	} elseif ($widget['type']=='custom') {
 		$output .= $header;
 		$output .= $widget['content'];
 	} elseif ($widget['type']=='sticky') {
 		$output .= $header;
 		if (isset($alert)) $output .= $alert;
 		$output .= "<form method=\"post\" action=\"#\">";
 		$output .= "<textarea id=\"stickynote\" name=\"stickynote\" class=\"stealth\" rows=\"6\">" . $widget['content'] . "</textarea>";
 		$output .= "<input type=\"submit\" value=\"Save\" />";
 		$output .= "</form>";
		
 	} elseif ($widget['type']=='db') {
		
 		if ($widget['name']=='projects') {
  			$output = '<li class="widget" id="projects" title="'.$widget['id'].'">';
			$output .= '<div class="widget-head"><h3><a href="'.$widget['link'].'">'.$widget['title'].'</a></h3>
 			<a class="rss" href="'.BASE_URL.'projects-feed.php?id='.$_COOKIE['user'].'"><img src="images/rss.png" alt="Subscribe to RSS" /></a>
 			</div><div class="widget-content">';
 			$projects = getMyProjects($_COOKIE['user']);
 			$output .= "<ul>";
 			foreach ($projects as $project) {
				$output .= '<li><a href="projectdetails.php?id='.$project['id'].'">'.$project['client'].' '.$project['name'].' <span>'.$project['id'].'</span></a></li>';
 			}
 			$output .= "</ul>";
			
 		} elseif ($widget['name']=='mytasks') {
 			$output = '<li class="widget" id="mytasks" title="'.$widget['id'].'">';
 			$output .= '<div class="widget-head"><h3><a href="'.$widget['link'].'">'.$widget['title'].'</a></h3>
 			<a class="rss" href="'.BASE_URL.'mytasks-feed.php?id='.$_COOKIE['user'].'"><img src="images/rss.png" alt="Subscribe to RSS" /></a>
 			</div><div class="widget-content">';
 			$mytasks = getMyTasks($_COOKIE['user']);
 			$output .= "<ul>";
 			foreach ($mytasks as $mytask) {
				$output .= '<li><a href="projectdetails.php?id='.$mytask['project_id'].'&task_id='.$mytask['id'].'#task-'.$mytask['id'].'">'.getClientName($mytask['project_id']).' '.$mytask['name'].' <span>'.$mytask['project_id'].'</span><span class="status '.$mytask['status'].'">'.$mytask['status'].'</span></a></li>';	
 			}
 			$output .= "</ul>";
			
 		} elseif ($widget['name']=='messages') {
 			$output = '<li class="widget" id="messages" title="'.$widget['id'].'">';
 			$output .= '<div class="widget-head"><h3>'.$widget['title'].'</h3>
			<a class="add" id="addmessage" href="#"> ADD</a>
 			</div><div class="widget-content">';
 			$messages = getMessages($_COOKIE['user']);
			if (isset($alertM)) $output .= $alertM;
 			$output .= "<ul>";
 			foreach ($messages as $message) {
				if ($message['sent_to'] == 0) {
					$sent_to = 'All: ';			 
				} else {
					$sent_to = 'You: ';	
				}
				$output .= '<li>'.$sent_to.$message['content'].'<br/><small>From: ' . getUsername($message['sent_from']) . '</small></li>';	
 			}
 			$output .= "</ul>";	
 		}
 	}
 	$output .= '</div></li>';
 	
	if ($widget['col'] == '0') {
		$col1 .= $output;
	} elseif ($widget['col'] == '1') {
		$col2 .= $output;	
	} elseif ($widget['col'] == '2') {
		$col3 .= $output;	
	}
}

//get header
require ('includes/header.php');
?> 
		<p class="options"><a id="addwidget" href="#">+ ADD WIDGET</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a id="colorSwitch" href="#">SWITCH COLOURS</a></p>	
		
		<div id="newwidget" title="Add a Widget" class="addtaskform">
			<form action="includes/addwidget.php" method="post">
				<input type="hidden" name="user_id" value="<?php echo $_COOKIE['user']; ?>" />
				<div><label for="title">Title</label>
				<input type="text" class="text" name="title" id="title" value="" /></div>
				<!--<div><label for="name">Name (A unique Name)</label>
				<input type="text" class="text" name="name" id="name" value="" /></div>-->
				<div><label for="type">Type:</label>
				<select name="type" id="type">
					<option value="rss">RSS Feed</option>
					<option value="custom">iGoogle Widget</option>
				</select></div>
				<div><label for="link">Link the widget heading to: (optional)</label>
				<input type="text" class="text" name="link" id="link" value="" /></div>
				<div><label for="content">Content (Paste RSS feed url or iGoogle gadget script here)</label>
				<textarea name="content" id="content"></textarea></div>
				<div><input type="submit" name="submit" value="Add" /></div>
			</form>
		</div>
		
		<div id="newmessage" title="New Message" class="addtaskform">
			<form action="#" method="post">
				<input type="hidden" name="sent_from" value="<?php echo $_COOKIE['user']; ?>" />
				<div><label for="sent_to">To:</label>
				<select name="sent_to" id="sent_to">
					<option value="0">All</option>
					<?php foreach ($users as $user) { ?>
					<option value="<?php echo $user['id']; ?>"><?php echo $user['username']; ?></option>
					<?php }  ?>
				</select></div>
				<div><label for="content">Message:</label>
				<textarea name="content" id="content"></textarea></div>
				<div><input type="submit" name="submit" value="Send" /></div>
			</form>
		</div>
 		
 		<div id="columns">
 			<ul id="column1" class="column">
 				<?php echo $col1; ?>
 				<li class="widget" id="intro">
 					<div class="widget-head">  
 						<h3>Blank</h3>
       				</div>
 					<div class="widget-content"></div>
 				</li>
 			</ul>
 			<ul id="column2" class="column">
 				<?php echo $col2; ?>
 			</ul>
 			<ul id="column3" class="column">
 				<?php echo $col3; ?>
 			</ul>
 		</div>
		
		<script type="text/javascript" src="js/inettuts.js"></script>  
	</body> 
</html>