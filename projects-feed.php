<?php
include_once('includes/config.php');
include_once('includes/functions.php');

$id = $_GET['id'];

$projects = getMyProjects($id);

header("Content-Type: application/xml; charset=ISO-8859-1"); 

	  
	  echo '<?xml version="1.0" encoding="ISO-8859-1" ?>
				<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
					<channel>
						<title>Limegreentangerine - Latest Projects</title>
						<atom:link href="'.BASE_URL.'projects-feed.php" rel="self" type="application/rss+xml" />
						<link>'.BASE_URL.'index.php</link>
						<description>Limegreentangerine - Latest Projects</description>
						';
						
		foreach ($projects as $project) {
			echo '<item>';
			echo '<title><![CDATA['. $project['name'] .']]></title>';
			echo '<description><![CDATA[
			Client: <![CDATA['.$project['client'].']]><br/>
			Client Contact: '. $project['contact'] .'<br/>
			Brief: '. $project['brief'] .'
			]]></description>';
			echo '<link><![CDATA['.BASE_URL.'projectdetails.php?id='. $project['id'].']]></link>';
			echo '</item>';
		}

		
		echo '</channel>
				</rss>';

		
					
?> 
	     
