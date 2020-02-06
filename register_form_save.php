<?php
echo "register form save WELCOME";
// we need to accept required data
// comfirm password
// and insert int personal data as pending user
$unit_id = $_POST['unit_id'];
$unit_password = $_POST['password'];
$comfirm_password = $_POST['comfirm_password'];

if($unit_password != $comfirm_password){
	header('location:register.php?msg=password does not match');
	return;
}

require('conn.php');

// we need to insert to those telegraph table
$sql="INSERT INTO personal (unit_id,password,role) VALUES ('$unit_id','$unit_password','pending')";

	$result=$conn->query($sql);
	if($result)
	{
		echo "registred successfully";
		echo "<br>sql is ".$sql;
		header("location:index.php?msg=Register Completed!<br>Your account needs to comfirm by Admin");
	}
	else
	{
		echo "Database insert  Error.".$conn->error;
	}
?>