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

    <title>ေၾကးနန္းစာ ေပးပို႕ျခင္း စနစ္</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">

  </head>

  <body>

    <div class="container">

      <form class="form-signin" action="login_form.php" method="post">

<?php
  if(isset($_GET['msg'])){
      echo '<div class="alert alert-danger">';
      echo '<strong>သတိေပးခ်က္! </strong> ';
      echo $_GET['msg'];
      echo '</div>';
  }
?>
     

      

        <h2 class="form-signin-heading">ေၾကးနန္းစာ ေပးပို႕ျခင္း စနစ္</h2>
        <h5 class="form-signin-heading">ေက်းဇူးျပဳျပီး စနစ္သို႕ ဝင္ပါ</h5>
        <br>
        <label for="inputEmail" class="sr-only">တပ္အမည္</label>
        <input type="text" id="username_input" class="form-control" placeholder="တပ္အမည္" required autofocus name="unit_id">
        <br>
        <label for="inputPassword" class="sr-only">စကားဝွက္</label>
        <input type="password" id="password_input" class="form-control" placeholder="စကားဝွက္" required name="password">

        <button class="btn btn-lg btn-success btn-block" type="submit">စနစ္သို႕ ဝင္ေရာက္မည္</button>

      </form>
      <form  class="form-signin"  action="register.php">

<br>
<br>
<button class="btn btn-lg btn-primary btn-block" type="submit">မွတ္ပံုတင္မည္</button>
      </form>
    </div> <!-- /container -->


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
