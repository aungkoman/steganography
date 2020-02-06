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

    <title>ဝင္စာမ်ား</title>


    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap-theme.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/jquery.loadingModal.min.css">
  </head>

  <body>

    <div class="container">


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
            <a class="navbar-brand" href="#">ေၾကးနန္း ေပးပို႕ျခင္း စနစ္</a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li><a href="main.php">မူလစာမ်က္ႏွာ</a></li>
              <li class="active"><a href="inbox.php">ဝင္စာမ်ား</a></li>
              <li><a href="outbox.php">ထြက္စာမ်ား</a></li>
              <li><a href="send.php">ေပးပို႕ရန္</a></li>
              <li><a href="users.php"> အသံုးျပဳသူမ်ား</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
              <li><a href="logout.php">စနစ္မွ ထြက္ရန္</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>


  <table class="table">
    <thead>
      <tr>
        <th>ေပးပိုႈသည့္ တပ္</th>
        <th>အေၾကာင္းအရာ</th>
          <th>ရက္စြဲ၊အခ်ိန္</th>
      </tr>
    </thead>
    <tbody>
  <?php
    // we query for sender's telegraph from telegraphs table and render in the following table
    require('security_layer.php');
    require('conn.php');
    $receiver = $_SESSION['unit_id'];
    $sql="SELECT * FROM telegraphs WHERE receiver ='$receiver'";
          $result=$conn->query($sql);
          if ($result->num_rows >0){
              while($row=$result->fetch_assoc()){

                echo "<tr>";
                echo "  <td>".$row['sender']."</td>";
                echo "  <td><a href='read.php?msg_id=".$row['db_id']."'>".$row['title']."</a></td>";
                echo "  <td>".$row['dto']."</td>";
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


    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.js"></script>
  <script src="js/jquery.loadingModal.min.js"></script>
    
    <script src="js/main.js"></script>

  </body>
</html>
