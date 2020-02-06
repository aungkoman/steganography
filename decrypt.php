<?php
	$password = isset($_POST['password']) ? $_POST['password'] : null;
	$image_code = isset($_POST['v']) ? $_POST['v'] : null;
	if($password == null || $image_code == null ) die("password and image code  is needed ");

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


  <link rel="stylesheet" href="css/bootstrap.min.css">

    <!-- Custom styles for this template -->
    <link href="css/navbar.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">


  <script src="js/jquery.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js'"></script>


  </head>

  <body>

    <div class="container">


      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <h2>ကြေးနန်းစာ ပေးပို့ခြင်း စနစ်</h2>    
         <h3>ပုံ ဖော် ခြင်း လုပ်ငန်း</h3>  	
         <p>လက်ရှိ အခြေအနေ :: <span id="progress_status"></span></p> 
  	<div class="progress" id="progress_bar_div">
    		<div class="progress-bar progress-bar-striped progress-bar-animated"  id="progress_bar" style="width:10%"></div>
  	</div>



<?php
// set miximum executin limit in second
// in this case 5 minutes
set_time_limit(300);
ini_set('memory_limit','-1');

###############################################################


# photo resources
$gray_photo = false; // image resource
$stegano_photo = false; // image resource

$image_filename = $image_code;
$gray_photo_url = "images/".$image_filename."gray_photo.png";
$stegano_photo_url = "images/".$image_filename.".png";

list($width, $height, $type, $attr) = getimagesize($stegano_photo_url);
$default_width = $width;
$default_height = $height;

function make_eight_bit($binary_string){
	//echo "binary string is ".$binary_string . "and it's count is ".strlen($binary_string); 
	$add_word_count = 8 - strlen($binary_string);
	//echo "<br> add word count is ".$add_word_count ;
	$add_word = "";
	for($i = 0 ; $i <$add_word_count ; $i++){
		$add_word .= "0";
	}
	$binary_string = $add_word.$binary_string;	
	return $binary_string;
}


# read stegano photo as resource
$stegano_photo = imagecreatefrompng($stegano_photo_url) ;
$gray_photo = $stegano_photo;
# initialize otsu threshold by giving image url
require('pwa_otsu.php');
$pwa_otsu = new Pwa_otsu($stegano_photo_url);
# initialize columanr transposition 
require('pwa_columnar.php');
$pwa_columnar = new Pwa_columnar();

$server_key = "whatisthislifeiffullofcare0wehavenotime0tostandandstarewhatisthislifeiffullofcare0wehavenotime0tostandandstarewhatisthislifeiffullofcare0wehavenotime0tostandandstarewhatisthislifeiffullofcare0wehavenotime0tostandandstarewhatisthislifeiffullofcare0wehavenotime0tostandandstarewhatisthislifeiffullofcare0wehavenotime0tostandandstarewhatisthislifeiffullofcare0wehavenotime0tostandandstarewhatisthis34256";
$user_key = $password.$server_key;

###########################################################################################


# getting one dimenstional array 
$transposited_gray_array = array();

$c = 0 ;
for($x = 0 ; $x<$default_width; $x++){
	$total = 800;
	$now = $x+1 ;
	$percent = (100 * $now ) / $default_width ;
	$pwa_otsu->send_to_browser("( ၁ / ၃ ) ကြေးနန်းစာကို စီစစ်ခြင်း  ".$percent.  " %  ပြီးစီးနေပါပြီ");
	$pwa_otsu->update_progressbar($percent);
	
	for($y =0; $y<$default_height; $y++){
		# read stegano image pixel value and extract r,g,b decimal
		$rgb = imagecolorat($stegano_photo, $x, $y);
		$r = ($rgb >> 16) & 0xFF; // Get R value 0-255 , last 3 digit
		$g = ($rgb >> 8) & 0xFF; // Get G value 0-255, last 3 digit
		$b = $rgb & 0xFF; // Get B value 0-255, last 2 digit

		# substract binary code from RGB 
		$r_binary = decbin($r);
		$r_binary = make_eight_bit($r_binary);
		$r_replace_value = substr($r_binary,5,3) ;

		$g_binary = decbin($g);
		$g_binary = make_eight_bit($g_binary);
		$g_replace_value = substr($g_binary,5,3) ;

		$b_binary = decbin($b);
		$b_binary = make_eight_bit($b_binary);
		$b_replace_value = substr($b_binary,6,2) ;

		$original_eight = $r_replace_value.$g_replace_value.$b_replace_value;
		$original_decimal = bindec($original_eight);


		// we have to make 8 digit by combining 3 phase
		// and change to decimal


		// making one dimensional binary array ( may be sorted 2 d table )
		$transposited_gray_array[$c] = $original_decimal ;
		$c++;
	}
}



################################################################################################

$pwa_otsu->send_to_browser("( ၂  /  ၃  ) စကားဝှက် အသုံးပြုရန် ပြင်ဆင်နေပါပြီ... ");
$pwa_otsu->update_progressbar(80);

$re_transposited_gray_array = $pwa_columnar->re_transposition($transposited_gray_array,$user_key);

//echo "<h3>re_transposited_binary_array count =>  ".count($re_transposited_binary_array) ."</h3>";

$c = 0 ;
for($x = 0 ; $x<$default_width; $x++){
	$now = $x+1 ;
	$percent = (100 * $now ) / $default_width ;
	$pwa_otsu->send_to_browser(" ( ၃ /  ၃ ) စကားဝှက် အသုံးပြုပြီး ပုံဖော်ခြင်း လုပ်ငန်း   ".$percent.  " %  ပြီးစီးနေပါပြီ");
	$pwa_otsu->update_progressbar($percent);
	
	for($y =0; $y<$default_height; $y++){
		$current_index = ($x*$default_width) + ($y);
		# get binary value
		$gary_value = $re_transposited_gray_array[$c];
		$c++;


		$newColor = $gary_value  << 16 | $gary_value << 8 | $gary_value ;

		// set color at x , y on binary image
		imagesetpixel($gray_photo, $x, $y, $newColor);
	}
}


//echo "debug array => ".json_encode($debug_array);
# write blank photo
# this photo will be retrieved binary (balck and white ) photo 
# write cover resource (may be it 'is stegano image ')
imagepng($gray_photo,$gray_photo_url);

echo "<br><h2> စကားဝှက် အသုံးပြုပြီး ပြန်လည်ထုတ်ယူရရှိလာသော  အဖြူအမည်း ဓာတ်ပုံ</h2>";
echo "<img src='".$gray_photo_url."'><br>";


$pwa_otsu->send_to_browser("ပုံဖော်ခြင်း လုပ်ငန်း ပြီးဆုံးပါပြီ ");

echo "<h3><a href='main.php'> မူလ စာမျက်နှာသို့ ပြန်သွားရန်</a></h3>";


?>

          </div>

    </div> <!-- /container -->

    
  </body>
</html>
