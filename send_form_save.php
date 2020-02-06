<?php
ini_set('memory_limit','-1');

require('security_layer.php');
require('conn.php');

// we need all form data 
$sender = $_SESSION['unit_id'];
$receiver = $_POST['receiver'];
$title = $_POST['title'];
$date = $_POST['date'];
$time = $_POST['time'];
$password = $_POST['password'];
$filedata = $_POST['filedata'];

$dto = $date." ".$time;

// we need to insert to those telegraph table
$sql="INSERT INTO telegraphs (sender,receiver,title,picture_data,dto) VALUES ('$sender','$receiver','$title','$filedata','$dto')";

	$result=$conn->query($sql);
	if($result)
	{
		echo "inserted successfully";
		header("location:main.php?msg=Message was sent to $receiver!");
	}
	else
	{
		echo "Database insert  Error.".$conn->error;
	}
?>