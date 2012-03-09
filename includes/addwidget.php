<?php
echo "<pre>";
print_r($_POST);
echo "</pre>";

include_once 'config.php';
include_once 'functions.php';

$user_id=$_POST['user_id'];
$type=$_POST['type'];
$link=$_POST['link'] ;
$content=$_POST['content'];
$title=$_POST['title'];

$dblink = dbconnect();

$query = "INSERT INTO widgets SET user_id = $user_id, name = '$name', type = '$type', title = '$title', link = '$link', content = '$content', col=2, sort_order=10 ";

$result = @mysql_query($query) or die(@mysql_error());
	if($result){
		//redirect
		header('location:../index.php');
		echo $query;
	}
	
	
	

dbclose($dblink);	

?>