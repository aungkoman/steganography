<?php
echo "change unit id role ";
echo $_GET['unit_id'];
echo $_GET['role'];


require('security_layer.php');

if ($_SESSION['role'] != 'admin'){
	header("location:main.php?msg=you don't have permission to enter user section");
	return;
}

require('conn.php');

$unit_id =  $_GET['unit_id'];
$role =  $_GET['role'];

if($role == 'admin' || $role == 'user' || $role == 'pending'){
	$sql="UPDATE `personal` SET `role` = '$role' WHERE `personal`.`unit_id` = '$unit_id'";
	$result=$conn->query($sql);
    if ($result  === TRUE){
    	// update success
    	// go to user page with noti
    	echo "update  $unit_id  as $role successfully. and sql is ".$sql;
		header("location:users.php?msg=update $unit_id  as $role successfully.");
    }
    else{
    	// update fail
    	// go to user page with error message
    	echo "faill  $unit_id  as $role ".$conn->error;
		header("location:users.php?msg=update $unit_id  as $role fail $conn->error.");
    }
} // end for if role


if($role== 'delete'){
	echo "it is delete section";
	$delete_sql="DELETE  FROM personal WHERE unit_id='$unit_id' ";
	$result = $conn->query($delete_sql);
	if($result === TRUE ){
		echo "Delete OPERATION IS SUCCESSFUL";
		header("location:users.php?msg=Delete $unit_id successfully.");
	}
	else {
		echo "Error found in Delete ".$conn->error;
		//echo "Delete OPERATION IS SUCCESSFUL";
		header("location:users.php?msg=Error found in Delete $conn->error");
	}
}

echo "end of world ";

/*
 if($role == 'delete'){
 	// we need to delete the user 
 	$delete_sql="DELETE  FROM personal WHERE unit_id='$unit_id' ";
	$result = $conn->query($delete_sql);
	if($result === TRUE ){
		//echo "Delete OPERATION IS SUCCESSFUL";
		header("location:users.php?msg=Delete $unit_id successfully.");
}
else {
	//echo "Error found in Delete ".$conn->error;
		//echo "Delete OPERATION IS SUCCESSFUL";
		header("location:users.php?msg=Error found in Delete $conn->error");
}
*/
 

?>