<?php
//echo "we just need temporary for 10 days (etc..)";
?>




<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>Inbox</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/navbar.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">
<?php
  if(isset($_GET['msg'])){
      echo '<div class="alert alert-info">';
      echo '<strong>Info : </strong> ';
      echo $_GET['msg'];
      echo '</div>';
  }
?>
      <!-- Static navbar -->
      <nav class="navbar navbar-default">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Telegraph System</a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li><a href="main.php">Home</a></li>
              <li><a href="inbox.php">Inbox</a></li>
              <li><a href="outbox.php">Outbox</a></li>
              <li><a href="send.php">Send</a></li>
              <li  class="active"><a href="#">Users</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
              <li><a href="logout.php">Logout</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>


  <table class="table">
    <thead>
      <tr>
        <th>Unit ID</th>
        <th>Role</th>
        <th>Admin</th>
        <th>User</th>
        <th>Pending</th>
        <th>Delete</th>
      </tr>
    </thead>
    <tbody>
  <?php
    // we query for sender's telegraph from telegraphs table and render in the following table
    require('security_layer.php');

    if ($_SESSION['role'] != 'admin'){
    	header("location:main.php?msg=you don't have permission to enter user section");
    	return;
	}
    require('conn.php');
    $sql="SELECT * FROM personal";
          $result=$conn->query($sql);
          if ($result->num_rows >0){
              while($row=$result->fetch_assoc()){

                echo "<tr>";
                echo "  <td>".$row['unit_id']."</td>";
                echo "  <td>".$row['role']."</a></td>";
                echo "  <td><a href='change_unit_id.php?unit_id=".$row['unit_id']."&role=admin'><button type='button' class='btn btn-default'>Make Admin</button></a></td>";
                echo "  <td><a href='change_unit_id.php?unit_id=".$row['unit_id']."&role=user'><button type='button' class='btn btn-default'>Make User</button></a></td>";
                echo "  <td><a href='change_unit_id.php?unit_id=".$row['unit_id']."&role=pending'><button type='button' class='btn btn-default'>Make Pending</button></a></td>";
                echo "  <td><a href='change_unit_id.php?unit_id=".$row['unit_id']."&role=delete'><button type='button' class='btn btn-default'>Delete</button></a></td>";
                echo "</tr>";
              }
          }
          else{
            // there is no telegraph for this unit_id
            echo "<tr>There is no telegraph for  ".$_SESSION['unit_id']."</tr>";
          }

  ?>
    </tbody>
  </table>



    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
