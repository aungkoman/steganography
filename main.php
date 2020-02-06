<?php
  session_start();
  if(!isset($_SESSION['role'])){

      header('location:index.php?msg=စနစ္အတြင္းသို႕ ဝင္ေပးပါ');
  }
  else if(isset($_SESSION['role']) && $_SESSION['role'] == 'pending'){
      header('location:index.php?msg=လူႀကီးမင္း၏ တပ္ိကု စနစ္ Admin မွ ခြင့္ျပဳခ်က္ ေပးရန္ လိုအပ္ပါသည္၊ ေက်းဇူးျပဳျပီး Admin ႏွင့္ ဆက္သြယ္ပါ');
  }

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

    <title><?php echo $_SESSION['unit_id']; ?></title>

    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap-theme.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/jquery.loadingModal.min.css">

  </head>

  <body>

    <div class="container">


<?php
  if(isset($_GET['msg'])){
      echo '<div class="alert alert-info">';
      echo '<strong>သတင္းအခ်က္အလက္  : </strong> ';
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
            <a class="navbar-brand" href="#">ေၾကးနန္း ေပးပို႕ျခင္း စနစ္</a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li class="active"><a href="#">မူလစာမ်က္ႏွာ</a></li>
              <li><a href="inbox.php">ဝင္စာမ်ား</a></li>
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

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <h1>ေၾကးနန္းစာ ေပးပို႕ျခင္း စနစ္</h1>
        <h2>မဂၤလာပါ  <b><?php echo $_SESSION['unit_id']; ?></b></h2>
        <p>ေၾကးနန္းစာ ေပးပို႕ျခင္း စနစ္မွ ႀကိဳစိုပါတယ္။</p>
      </div>

    </div> <!-- /container -->



    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.js"></script>
  <script src="js/jquery.loadingModal.min.js"></script>
    
    <script src="js/main.js"></script>


  </body>
</html>
