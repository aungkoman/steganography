<?php
  session_start();
  if(!isset($_SESSION['role'])){

      header('location:index.php?msg=Login first.');
  }
  else if(isset($_SESSION['role']) && $_SESSION['role'] == 'pending'){
      header('location:index.php?msg=You are require to approve by admin');
  }

?>
