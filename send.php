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

<style>
  .thumb2 {
    width: : 300px;
    height: 300px;
    border: 1px solid #000;
    margin: 10px 5px 0 0;
  }
  .thumb {
    width:800px;
    height: 600px;
  }
</style>
    <title>ကြေးနန်း ပေးပို့ခြင်း </title>




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
            <a class="navbar-brand" href="#">ကြေးနန်း ပေးပို့ခြင်း စနစ်</a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li><a href="main.php">မူလစာမျက်နှာ</a></li>
              <li><a href="inbox.php">ဝင်စာများ</a></li>
              <li><a href="outbox.php">ထွက်စာများ</a></li>
              <li class="active"><a href="send.php">ပေးပို့ရန်</a></li>
              <li><a href="users.php"> အသုံးပြုသူများ</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
              <li><a href="logout.php">စနစ်မှ ထွက်ရန်</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>


  <form class="form-horizontal" action="send_form.php" method="post">



  <div class="form-group">
      <label class="control-label col-sm-2" for="pwd">မူလ ဓာတ်ပုံ  :</label>
      <div class="col-sm-10">       
              <input type="file" id="original_photo" accept="image/x-png,image/gif,image/jpeg" onchange="readURL(this);"   />
              <!--span id="cover_photo_span"></span-->
              <img id="original_photo_img"  class="thumb" alt="မူလ ဓာတ်ပုံ ရွေးချယ်ပေးပါ">
              <input type="text" class="" id="original_photo_data" name="original_photo_data"  required>
      </div>
    </div>



<div class="form-group">
  <label class="control-label col-sm-2" for="pwd">ကာဘာ ဓာတ်ပုံ :</label>
  <div class="col-sm-10">       
          <input type="file" id="cover_photo"  accept="image/x-png,image/gif,image/jpeg" onchange="readURLcover(this);"   />
          <!--span id="cover_photo_span"></span-->
          <img id="cover_photo_img"  class="thumb" alt="ကာဗာဓာတ်ပုံ ရွေးချယ်ပေးပါ">
          <input type="text" class="" id="cover_photo_data" name="cover_photo_data" required>
  </div>
</div>



    <div class="form-group">
      <label class="control-label col-sm-2" for="pwd">စကားဝှက် :</label>
      <div class="col-sm-10">          
        <input type="text" class="form-control" placeholder="ကြေးနန်း စကားဝှက် " name="password">
      </div>
    </div>


    <div class="form-group">        
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-default" id="send_button">ပေးပို့မည်</button>
      </div>
    </div>
    
  </form>

    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="assets/js/ie10-viewport-bug-workaround.js"></script>

<script>
  if (window.File && window.FileReader && window.FileList && window.Blob) {
  // Great success! All the File APIs are supported.
  console.log("The File APIs are fully supported by your browser");
} else {
  alert('The File APIs are not fully supported in this browser.');
}

// original image preview
function readURL(input) {//================Photo ???????????????????????????????????====================
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#original_photo_img').attr('src', e.target.result);
                var original_photo_data = document.getElementById('original_photo_data');
                original_photo_data.value = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
    }
  
    
// cover image preview
function readURLcover(input) {//================Photo ???????????????????????????????????====================
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#cover_photo_img').attr('src', e.target.result);
                var cover_photo_data = document.getElementById('cover_photo_data');
                cover_photo_data.value = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
    }
</script>


    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.js"></script>
  <script src="js/jquery.loadingModal.min.js"></script>
    
    <script src="js/main.js"></script>
    <script>
      $("#send_button").on("click",function(){
        show_loading_modal("ဓာတ်ပုံများ ဆာဗာသို့ ပေးပို့နေပါပြီ  ... ");
      })
    </script>

  </body>
</html>
