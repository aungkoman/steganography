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

    <title>စာဖက္ခန္း</title>



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
              <li><a href="#">မူလစာမ်က္ႏွာ</a></li>
              <li><a href="inbox.php">ဝင္စာမ်ား</a></li>
              <li><a href="outbox.php">ထြက္စာမ်ား</a></li>
              <li><a href="send.php">ေပးပို႕ရန္</a></li>
              <li><a href="users.php"> အသံုးျပဳသူမ်ား</a></li> 
              <li class="active"><a href="#">စာဖက္ခန္း</a></li>
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
        <th>ေပးပို႕</th>
        <th>လက္ခံ</th>
        <th>အေၾကာင္းအရာ</th>
        <th>ေၾကးနန္း</th>
          <th>ရက္စြဲ၊အခ်ိန္</th>
        <th>စာဖတ္ရန္ </th>
      </tr>
    </thead>
    <tbody>

<?php
//echo "this is message read page for message id ".$_GET['msg_id'];

    require('security_layer.php');
    require('conn.php');


    $db_id = $_GET['msg_id'];
    $sql="SELECT * FROM telegraphs WHERE db_id ='$db_id'";
          $result=$conn->query($sql);
          if ($result->num_rows >0){
              $row=$result->fetch_assoc();
                echo "<tr>";
                echo "  <td>".$row['sender']."</td>";
                echo "  <td>".$row['receiver']."</td>";
                echo "  <td>".$row['title']."</a></td>";
                echo "  <td><img src='".$row['picture_data']."' alt='this is telegrap'></td>";
                echo "  <td>".$row['dto']."</td>";
                echo "  <td>";
                echo "<span class='hidden'>".$row['db_id']."</span>";
                echo '<button class="btn" data-toggle="modal" data-target="#read_modal" id="read_modal_button">';
                echo 'စာဖတ္ရန္';
                echo '</button>';
                echo "</tr>";
                echo "</td>";
          }
?>



    </tbody>
  </table>

            <!-- new soldier modal STAT -->
      <div class="modal fade" id="read_modal">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">ပိတ္မည္</span></button>

                      <h4 class="modal-title">စာဖတ္ရန္</h4>
                  </div>
                  <div class="modal-body" id="read_modal_body_div">
                    <form action='decrypt.php' method='post'>
                      <input type='number' class='hidden' name='telegraph_id' id='telegraph_id' />
                      <label>ေၾကးနန္း စကားဝွက္ : </label>
                      <input type='text' name='telegraph_pw' id='telegraph_pw' />
                      <input type='submit' value='ဖတ္မည္' />
                    </form>


                  </div>
                  <!-- this is modal footer section that we need to hide -->
                  <!--
                  <div class="modal-footer">
                      <button class="btn btn-primary" id="submit_soldier">Submit Soldier</button>
                  </div>
                  <-->
              </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->



    </div> <!-- /container -->


    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.js"></script>
  <script src="js/jquery.loadingModal.min.js"></script>
    
    <script src="js/main.js"></script>

    <script>
      // on click listner
      $("#read_modal_button").on('click',function(){
        console.log("read is clicked");
        $("#telegraph_id").val($(this).prev().text());
      });

    </script>

  </body>
</html>
