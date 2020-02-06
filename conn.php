<?php
/* local development database server */

$servername = "localhost";
$username = "root";
$password = "";
$db_name = "phyowaiaung";

$conn = new mysqli($servername, $username, $password, $db_name);

if($conn->connect_error){
	//echo "error in connection ". $conn->connect_error;
}
else {
	//echo "<br> Database Connected ";
}


?>

<?php
/* server */
/* production database server credential */ 

/*
$servername = "localhost";
$username = "id7717095_phyowaiaung";
$password = "pwadbpass";
$db_name = "id7717095_phyowaiaung";

$conn = new mysqli($servername, $username, $password, $db_name);

if($conn->connect_error){
	//echo "error in connection ". $conn->connect_error;
}
else {
	//echo "<br> Database Connected ";
}
*/
?>