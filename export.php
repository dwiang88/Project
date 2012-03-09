<?php 

include_once('includes/config.php');
include_once('includes/functions.php');

if (!isset($_COOKIE['user'])) {
	//no cookie is found so redirect to setup.php
	header("location:setup.php");
}

if(isset($_GET['a'])) {
	if ($_GET['a'] == 'billing') {
		$filename = 'FOR_BILLING_' . date("d-m-y") . '.csv';
		$projects = getCompletedProjects();
	} elseif ($_GET['a'] == 'archive'){
		$filename = 'ARCHIVED_' . date("d-m-y") . '.csv';
		$projects = getArchivedProjects();
	} elseif($_GET['a'] == 'live') {
		if(isset($_GET['u'])) {
			$filename = 'MY_LIVE_PROJECTS_' . date("d-m-y") . '.csv';
			$projects = getMyProjects($_GET['u']);
		} else {
			$filename = 'LIVE_PROJECTS_' . date("d-m-y") . '.csv';
			$projects = getProjects();
		}
	}
} else {
	$filename = 'LIVE_PROJECTS_' . date("d-m-y") . '.csv';
	$projects = getProjects();
}

$content = "id,client,project,contact,purchase order,total billable items,total time,quoted price \n";

foreach ($projects as $project) {  	
	$content .= $project['id'].',';
	$content .= '"'.$project['client'].'",';
	$content .= '"'.$project['name'].'",';
	$content .= '"'.$project['contact'].'",';
	$content .= '"'.$project['porder'].'",';
	$content .= '"'.billableAmount($project['id']).'",';
	$content .= '"'.projectMinutes($project['id']).'",';
	$content .= '"'.$project['quoted'].'"';
	$content .= "\n";
}

header('Content-Type: application/csv'); 
header("Content-disposition: csv" . date("Y-m-d") . ".csv");
header( "Content-disposition: filename=".$filename);
print ($content);
exit;