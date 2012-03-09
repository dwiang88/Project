<?php
include_once('includes/config.php');
include_once('includes/functions.php');

$id = $_GET['id'];

$tasks = getMyTasks($id);

header("Content-Type: application/xml; charset=ISO-8859-1"); 

	  
	  echo '<?xml version="1.0" encoding="ISO-8859-1" ?>
				<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
					<channel>
						<title>Limegreentangerine - My Tasks</title>
						<atom:link href="'.BASE_URL.'mytasks-feed.php?id='.$id.'" rel="self" type="application/rss+xml" />
						<link>'.BASE_URL.'index.php</link>
						<description>Limegreentangerine - My Tasks</description>
						';
						
		foreach ($tasks as $task) {
		
			$projectName =  getProjectName($task['project_id']);
			
			echo '<item>';
			echo '<title><![CDATA[' . $projectName . ' - ' . $task['name'] .']]></title>';
			echo '<description><![CDATA[
			Project ID: '.$task['project_id'].'<br/>
			Project Name: '.$projectName.'<br/>
			Status: '. $task['status'] .'<br/>
			Priority: '. $task['priority'] .'<br/>
			Deadline: '. $task['deadline'] .'<br/>
			Notes: '. $task['notes'] .'
			]]></description>';
			echo '<link><![CDATA['.BASE_URL.'projectdetails.php?id='. $task['project_id'].'&task_id='. $task['id'] .'#task-'.$task['id'].']]></link>';
			echo '</item>';
		}

		
		echo '</channel>
				</rss>';

		
					
?> 
	     
<?php echo getProjectName($task['project_id']); ?>