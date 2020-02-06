<?php
echo "this page will check login information and redirect to second page";
echo "<br>";
echo "<a href='main.php'> Go to Main Page</a>";


$unit_id = $_POST['unit_id'];
$unit_password = $_POST['password'];

//echo "<br><br>Welcome to ".$unit_id. " and password ".$unit_password;



$servername = "localhost";
$username = "root";
$password = "";
$db_name = "phyowaiaung";

$conn = new mysqli($servername, $username, $password, $db_name);

if($conn->connect_error){
	die( "error in connection ". $conn->connect_error);
}
else {
	//echo "<br> Database Connected ";
}


$sql="SELECT * FROM personal WHERE unit_id='$unit_id' AND password='$unit_password'";
$result=$conn->query($sql);

//echo "<br> result is ".$result->num_rows;


if ($result->num_rows >0){
	//echo "<br>login success";
	$user_row=$result->fetch_assoc();
	//echo "<br> user row is ".json_encode($user_row);

	session_start();
	$_SESSION['unit_id']=$user_row['unit_id'];
	$_SESSION['password']=$user_row['password'];
	$_SESSION['role']=$user_row['role'];

	// redirect to main page
	header('location:main.php?msg=စနစ္အတြင္းသို႕ ေအာင္ျမင္စြာ ဝင္ေရာက္ျပီးပါျပီ');
}
else{
	//echo "<br>unit_id and password does not match for querying ".$sql;
     header('location:index.php?msg=တပ္အမည္ႏွင့္ စကားဝွက္ ကိုက္ညီမႈ မရိွပါ');
}


?>