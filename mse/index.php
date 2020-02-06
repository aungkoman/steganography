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
  .thumb {
    width: : 800px;
    height: 600px;
    border: 1px solid #000;
    margin: 10px 5px 0 0;
  }
</style>
    <title>ေၾကးနန္း ေပးပို႕ျခင္း </title>




    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/bootstrap-theme.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/jquery.loadingModal.min.css">
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
              <li><a href="../main.php">မူလစာမ်က္ႏွာ</a></li>
              <li><a href="../inbox.php">ဝင္စာမ်ား</a></li>
              <li><a href="../outbox.php">ထြက္စာမ်ား</a></li>
              <li><a href="../send.php">ေပးပို႕ရန္</a></li>
              <li class="active"><a href="index.php">MSE</a></li>
              <li><a href="../users.php">အသံုးျပဳသူမ်ား</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
              <li><a href="logout.php">စနစ္မွ ထြက္ရန္</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>


  <form class="form-horizontal" action="mse.php" method="post">


    <div class="form-group">
      <label class="control-label col-sm-2" for="pwd">မူလ ဓာတ္ပံု :</label>
      <div class="col-sm-10">       
              <input type="file" id="cover_photo" />
              <!--span id="cover_photo_span"></span-->
              <img id="cover_photo_img"  class="thumb" alt="ကာဗာဓာတ္ပံု ေရြးခ်ယ္ေပးပါ">
              <input type="text" class="hidden" id="cover_photo_data" name="cover_photo_data" required>
      </div>
    </div>



    <div class="form-group">
      <label class="control-label col-sm-2" for="pwd">ေျပာင္းလဲသြားေသာ ဓာတ္ပံု :</label>
      <div class="col-sm-10">       
              <input type="file" id="stegano_photo" />
              <!--span id="cover_photo_span"></span-->
              <img id="stegano_photo_img"  class="thumb" alt="ေျပာင္းလဲသြားေသာ ေရြးခ်ယ္ေပးပါ">
              <input type="text" class="hidden" id="stegano_photo_data" name="stegano_photo_data" required>
      </div>
    </div>



    <div class="form-group">        
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-default" id="send_button">MSE တန္ဖိုး တြက္မည္</button>
      </div>
    </div>
    
  </form>

    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>

<script>
  if (window.File && window.FileReader && window.FileList && window.Blob) {
  // Great success! All the File APIs are supported.
  console.log("The File APIs are fully supported by your browser");
} else {
  alert('The File APIs are not fully supported in this browser.');
}

  function handleFileSelect(evt) {
    var files = evt.target.files; // FileList object
    // Loop through the FileList and render image files as thumbnails.
    for (var i = 0, f; f = files[i]; i++) {
      // Only process image files.
      if (!f.type.match('image.*')) {
        continue;
      }
      var reader = new FileReader();
      // Closure to capture the file information.
      reader.onload = (function(theFile) {
        return function(e) {
          // Render thumbnail.
          var span = document.createElement('span');
          console.log("File name is "+theFile.name);
          console.log("File Size is  "+theFile.size);
          span.innerHTML = ['<img class="thumb" src="', e.target.result,
                           '" title="', escape(theFile.name), '"/>'].join('');
          //document.getElementById('list').insertBefore(span, null);
          //document.getElementById('cover_photo_span').innerHTML=span.innerHTML;
          var cover_photo_img = document.getElementById('cover_photo_img');
          var cover_photo_data = document.getElementById('cover_photo_data');
          cover_photo_img.setAttribute('src', e.target.result);
          cover_photo_data.value = e.target.result;
          cover_photo_span_change();
        };
      })(f);
      // Read in the image file as a data URL.
      reader.readAsDataURL(f);
    }
  }

  function handleSteganoFileSelect(evt) {
    var files = evt.target.files; // FileList object

    // Loop through the FileList and render image files as thumbnails.
    for (var i = 0, f; f = files[i]; i++) {

      // Only process image files.
      if (!f.type.match('image.*')) {
        continue;
      }


      var reader = new FileReader();

      // Closure to capture the file information.
      reader.onload = (function(theFile) {
        return function(e) {
          // Render thumbnail.
          var span = document.createElement('span');
          console.log("File name is "+theFile.name);
          console.log("File Size is  "+theFile.size);

          span.innerHTML = ['<img class="thumb" src="', e.target.result,
                           '" title="', escape(theFile.name), '"/>'].join('');
          //document.getElementById('list').insertBefore(span, null);
          //document.getElementById('cover_photo_span').innerHTML=span.innerHTML;

          var stegano_photo_img = document.getElementById('stegano_photo_img');
          var stegano_photo_data = document.getElementById('stegano_photo_data');
          stegano_photo_img.setAttribute('src', e.target.result);
          stegano_photo_data.value = e.target.result;
          cover_photo_span_change();
        };
      })(f);

      // Read in the image file as a data URL.
      reader.readAsDataURL(f);
    }
  }

  function cover_photo_span_change(){
    console.log("changing cover photo...");
  }
  //document.getElementById('files').addEventListener('change', handleFileSelect, false);
  document.getElementById('cover_photo').addEventListener('change', handleFileSelect, false);
  document.getElementById('stegano_photo').addEventListener('change', handleSteganoFileSelect, false);
</script>

  <script src="js/jquery.loadingModal.min.js"></script>
    
    <script src="js/main.js"></script>
    <script>
      $("#send_button").on("click",function(){
        show_loading_modal("ဓာတ္ပံုမ်ား ဆာဗာသို႕ ေပးပို႕ေနပါျပီ  ... ");
      })
    </script>

  </body>
</html>
