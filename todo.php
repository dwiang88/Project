<?php 
$tab = "todos";

include_once('includes/config.php');
include_once('includes/functions.php');

if (!isset($_COOKIE['user'])) {
	//no cookie is found so redirect to setup.php
	//header("location:setup.php");
}

$users = getAllUsers();

if (!isset($_GET['u'])) {
	$u = $_COOKIE['user'];
} else {
	$u = $_GET['u'];
}

if (!isset($_GET['wc'])) {
	if (date('l') != 'Monday') {
		//$wc = date_format( date_create("last monday"), "Y-m-d" );
		$wc = date('Y-m-d', strtotime("last Monday"));
	} else {
		$wc = date('Y-m-d');
	}
} else {
	$wc = $_GET['wc'];
}

if (isset($_REQUEST['name'])) {
	if ($_REQUEST['id'] == '') { //are we editing or adding
		addTodo($u, $_REQUEST['name'], $_REQUEST['date']);
	}  else {
		editTodo($u, $_REQUEST['name'], $_REQUEST['date'], $_REQUEST['id']);
	}
}


$userName = ucfirst(getUsername($u));
$pageName = $userName . "&rsquo;s Todos";
$info = "All todo's assigned to  ".$userName.".";

//get header
require ('includes/header.php');
?>						
		<p class="options"><a id="colorSwitch" href="#">SWITCH COLOURS</a></p>
				
 		<?php require('includes/nav.php'); ?>

 		<div class="content">
 			
 			<form id="filter" action="" method="get">
 				<label for="u">Show: </label>
 				<select name="u" class="trigger" id="u">
 					<option value="0">-- Unassigned -- </option>
 					<?php foreach ($users as $user) { ?>
 						<option value="<?php echo $user['id']; ?>" <?php if ($user['id'] == $u) echo 'selected="selected"'; ?>><?php echo ucfirst($user['username']); ?></option>
 					<?php }  ?>
 				</select>
 			</form>
 			<br class="clear"/>
 			
		<ul class="week"> 
			<?php for ($i = 0; $i <= 4; $i++) { 
				$theDay = strtotime("$wc + $i day");
				
				if(date('Y-m-d', $theDay) == date('Y-m-d')) {
					$today = 'today';
				} else {
					$today = '';
				}
				echo '<li>';
			   	echo '<h3>'. date('l', $theDay) .'</h3>';
			   	echo '<h4>'. date('F d, Y', $theDay) .'</h4>';
			   	?>
			   		<form method="post" action="" class="addtodo">
			   			<input type="hidden" name="date" value="<?php echo date('Y-m-d', $theDay) ?>" />
			   			<input type="hidden" name="wc" value="<?php echo $wc ?>" />
			   			<input type="hidden" name="id" class="id" value="" />
			   			<input type="text" name="name" value="" class="input" />
			   		</form>
			   	<?php 
			   	echo '<ul class="day '. $today .'" id="'. date('l', $theDay) .'" rel="'.date('Y-m-d', $theDay).'">';
			   	$todos = getDaysTodos($u, date('Y-m-d', $theDay));
			   	foreach ($todos as $todo) {
			   		if ($todo['type'] == 1) {
			   			$taskdetails = getTask($todo['task_id']);
			   			
			   			//is the deadline approaching?
			   			$daystildeadline = 0;
			   			if ($taskdetails[0]['deadline']) {
			   				$daystildeadline = (strtotime($taskdetails[0]['deadline']) - $theDay) / 86400;
			   			}
			   			?>
			   			<li id="<?php echo $todo['id'] ?>" class="<?php if($todo['done']) echo " done"; ?> <?php if($daystildeadline < 0) echo " overdue"; ?>">
			   				<?php echo getProjectName($taskdetails[0]['project_id']).' '. $taskdetails[0]['name'] ?>
			   				
			   				<?php if ($daystildeadline < 10 && $daystildeadline > 0 && !$todo['done'] && $daystildeadline ) {
			   					 echo '<a href="projectdetails.php?id='. $taskdetails[0]['project_id'] .'&task_id='. $taskdetails[0]['id'] .'#task-'. $taskdetails[0]['id'] .'" class="due">'.$daystildeadline . '</a> '; 
			   					 } else { 
			   					 	echo '<a href="projectdetails.php?id='. $taskdetails[0]['project_id'] .'&task_id='. $taskdetails[0]['id'] .'#task-'. $taskdetails[0]['id'] .'" class="go"><img src="images/go.gif" /></a> ';
			   					 }
			   					?>			   				
			   			</li>
			   			<?php 
			   		} else {
			   			?>
			   				<li id="<?php echo $todo['id'] ?>" class="manualtodo <?php if($todo['done']) echo " done"; ?>">
			   					<span><?php echo $todo['name'] ?></span>
				   				<a href="#" class="delete" id="<?php echo $todo['id'] ?>"><img src="images/delete.gif" /></a>
			   				</li>
			   			<?php
			   		}

			   	} 
			   	echo '</ul>';
			   	echo '</li>';
			 } ?>
 	</ul>
 			 			
 	<p style="clear:both; margin-top:40px; border-top: 1px solid #ccc; padding-top:10px; text-align: right">
 		<a href="todo.php?wc=<?php echo date('Y-m-d', strtotime("$wc - 1 day"));  ?>">&laquo; Prev</a> | 
 		<a href="todo.php?wc=<?php echo date('Y-m-d', strtotime("$wc + 1 day"));  ?>">Next &raquo;</a>
 	</p>
 	
 	<hr/>
 	<h3>SOMEDAY</h3>
 	<hr/>
 	<ul id="someday" rel="2076-12-27" class="day">
 	<?php 
 		$todos = getDaysTodos($u,'2076-12-27');
 		foreach ($todos as $todo) {
 			if ($todo['type'] == 1) {
	 			$taskdetails = getTask($todo['task_id']);
	 			//is the deadline approaching?
	 			$daystildeadline = 0;
	 			if ($taskdetails[0]['deadline']) {
	 				$daystildeadline = (strtotime($taskdetails[0]['deadline']) - $theDay) / 86400;
	 			}
	 			?>
	 				<li id="<?php echo $todo['id'] ?>" class="<?php if($todo['done']) echo " done"; ?> <?php if($daystildeadline < 0) echo " overdue"; ?>">
	 					<?php if ($daystildeadline < 10 && $daystildeadline > 0 && !$todo['done'] && $daystildeadline ) echo '<span class="due">'.$daystildeadline . '</span> '; ?>
	 					<?php echo getProjectName($taskdetails[0]['project_id']).' '. $taskdetails[0]['name'] ?>
	 					<a href="projectdetails.php?id=<?php echo $taskdetails[0]['project_id'] ?>&task_id=<?php echo $taskdetails[0]['id'] ?>#task-<?php echo $taskdetails[0]['id'] ?>" class="go"><img src="images/go.gif" /></a>
	 				</li>
	 			<?php
	 		} else {
	 			?>
	 				<li id="<?php echo $todo['id'] ?>" class="manualtodo <?php if($todo['done']) echo " done"; ?>">
	 					<span><?php echo $todo['name'] ?></span>
	 				</li>
	 			<?php
	 		}
 		}
 	?>
 	</ul>
 	
 		</div>
		<script type="text/javascript" src="js/jquery.dataTables.min.js"></script> 
		<script type="text/javascript">
			function toggleDone(todo) {
				$(todo).toggleClass('done');
				data = '&id=' + $(todo).attr('id');
				$.get("includes/toggleDone.php", data);
			}
			
			function saveOrder() {
				data = '';
				//build a query string
				$('.day').each(function(i){
					var date = $(this).attr('rel'); //the date its been moved to
					var weekday = $(this).attr('id'); //the todo id
					$('#' + weekday + ' li').each(function(i){
						//loop through all in this list
						data += '&todo' + $(this).attr('id') + '[id]=' + $(this).attr('id');
						data += '&todo' + $(this).attr('id') + '[date]=' + date;
						data += '&todo' + $(this).attr('id') + '[sort_order]=' + i; 
					});
				});
				//send query string to updatetodos.php
				$.get("includes/updatetodos.php", data);
			}
			
			var wasDragged = false;
		
			$(document).ready(function() {
							
				$( ".day" ).sortable({ 
					connectWith: '.day',
					start: function(event, ui) {
						wasDragged = true;
					},
					update: function(event, ui) {
					    saveOrder();
					}
				});
				
				$(".day li").click(function() {
					var todo = this;
					if (wasDragged) {
						wasDragged = false;
					} else {
						toggleDone(todo);
					}
				});
				
				
				$(".go, .due").click(function() {
					window.location($(this).attr('href'));
					return false;
				});


				$(".delete").click(function() {
					data = '&id=' + $(this).attr('id');
					$.get("includes/deletetodos.php", data);
					$(this).parent().fadeOut();
					return false;
				});
				
				$('.input').droppable({ 
					accept: '.manualtodo', 
					drop: function(event, ui) { 
						 $(ui.draggable).hide('.day li');
						 var c = $(ui.draggable).text().trim();
						 var i = $(ui.draggable).attr('id');
						 $(this).val(c);
						 $('.id').val(i);
					}
				}); 
				
			});
		</script>
		
		<!--[if lte IE 7]>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/chrome-frame/1/CFInstall.min.js"></script>
		<script>
			function showPrompt() {
				alert('install GCF');
			}
			
			window.attachEvent("onload", function() {
			   CFInstall.check({
			     preventPrompt: "true",
			     onmissing: showPrompt()
			   });
			});

		  </script>
		<![endif]-->
	</body> 
</html>