
<!-- what we do -->
<!--
	#1. get user key
	#2. get telegraph id
	#3. get stegano telegraph 
	#4. transpost
	#5 decrypt
	#6 show to user 
-->

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
        <h2>ေၾကးနန္းစာ ေပးပို႕ျခင္း စနစ္</h2>    
         <h3>ပံု ေဖာ္ ျခင္း လုပ္ငန္း</h3>  	
         <p>လက္ရိွ အေျခအေန :: <span id="progress_status"></span></p> 
  	<div class="progress" id="progress_bar_div">
    		<div class="progress-bar progress-bar-striped progress-bar-animated"  id="progress_bar" style="width:10%"></div>
  	</div>



<?php
// set miximum executin limit in second
// in this case 5 minutes
set_time_limit(300);
ini_set('memory_limit','-1');

###############################################################

# it is database picture data
list($first,$second) = explode(',',$telegraph_data['picture_data']);
$pic = base64_decode($second);
# write user post data with user title on root folder
$photo_data_url = "temp_img/".$telegraph_data['title']."_database";
file_put_contents($photo_data_url, $pic);

# to get media type
list($first,$second) = explode(',',$telegraph_data['picture_data']);
list($data,$encoding) = explode(';', $first);
list($data,$type_pair) = explode(':', $data);
list($media,$media_type) = explode('/',$type_pair);



# get file name and server cover name url
$filename = "title";
$binary_cover_url = "images/binary_cover.png";
$cover_url = "images/cover_photo.png";
$original_url = "images/original_photo.png";

$gray_url = "images/gray_photo.png";
$binary_url = "images/binary_photo.png";

$stegano_url = "images/stegano_photo.png";
$binary_stegano_url = "images/binary_stegano_photo.png";

$retrieve_url = "images/retrieve_photo.png";
$binary_retrieve_url = "images/binary_retrieve_photo.png";

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



# read database photo data
# may be png type
$stegano_photo = false;
if($media_type != 'png' && $media_type != 'jpeg'){
	die("we only support png and jpeg for cover photo");
}
if($media_type == 'png') $stegano_photo = imagecreatefrompng($photo_data_url) ;
if($media_type == 'jpeg') $stegano_photo = imagecreatefromjpeg($photo_data_url) ;


# initialize otsu threshold by giving image url
require('pwa_otsu.php');
$pwa_otsu = new Pwa_otsu($photo_data_url);
# we don't need threshold value in decryption
//$value = $pwa_otsu->otus_threshold();


# initialize columanr transposition 
require('pwa_columnar.php');
$pwa_columnar = new Pwa_columnar();


//$threshold_value = $value;

// now we have user key (password)
// we need one dimentsional array for binary and grayscale value 
// ok two array we need
$server_key = "whatisthislifeiffullofcare0wehavenotime0tostandandstarewhatisthislifeiffullofcare0wehavenotime0tostandandstarewhatisthislifeiffullofcare0wehavenotime0tostandandstarewhatisthislifeiffullofcare0wehavenotime0tostandandstarewhatisthislifeiffullofcare0wehavenotime0tostandandstarewhatisthislifeiffullofcare0wehavenotime0tostandandstarewhatisthislifeiffullofcare0wehavenotime0tostandandstarewhatisthis34256";
$user_key = $telegraph_pw.$server_key;
//$user_key = $telegraph_pw;

###########################################################################################


# binary stegano decoding process
# read blank photo
# read blank photo to make binary photo
$white_url = "images/white_photo.png";
$binary_photo = imagecreatefrompng($white_url);

# read binary stegano photo 
$binary_stegano_photo = imagecreatefrompng($photo_data_url);

# getting one dimenstional array 
$transposited_binary_array = array();

$c = 0 ;
for($x = 0 ; $x<800; $x++){
	$total = 800;
	$now = $x+1 ;
	$percent = (100 * $now ) / $total ;
	$pwa_otsu->send_to_browser("( ၁ / ၃ ) ေၾကးနန္းစာကို စီစစ္ျခင္း  ".$percent.  " %  ျပီးစီးေနပါျပီ");
	$pwa_otsu->update_progressbar($percent);
	
	for($y =0; $y<600; $y++){
		# read stegano image pixel value and extract r,g,b decimal
		$rgb = imagecolorat($binary_stegano_photo, $x, $y);
		$r = ($rgb >> 16) & 0xFF;

		# change to binary
		$r_binary = decbin($r);

		# make 8 bit binary string
		$r_binary = make_eight_bit($r_binary);

		# extract the last  binary string from r
		$r_replace_value = substr($r_binary,7,1) ;
		// making one dimensional binary array ( may be sorted 2 d table )

		$transposited_binary_array[$c] = $r_replace_value ;
		$c++;

				# if 1 , change to 255
		# if 0 , will be 0 :D :D :D
		if($r_replace_value == 1) {
			$r_replace_value = 255 ;
		}
		
		
		// initialize new color
		$gray_decimal = $r_replace_value;

		// initialize new color
		$newColor = $gray_decimal  << 16 | $gray_decimal << 8 | $gray_decimal ;

		// set color at x , y on binary image
		imagesetpixel($binary_photo, $x, $y, $newColor);
	}
}


imagepng($binary_photo,$binary_retrieve_url);

echo "<br><h2>စကားဝွက္ မသံုးပဲ ျပန္လည္ ထုုတ္ယူ ရရိွေသာ  အျဖဴအမည္း ဓာတ္ပံု</h2>";
echo "<img src='".$binary_retrieve_url."'><br>";


################################################################################################

$pwa_otsu->send_to_browser("( ၂  /  ၃  ) စကားဝွက္ အသံုးျပုရန္ ျပင္ဆင္ေနပါျပီ... ");
$pwa_otsu->update_progressbar(80);

$re_transposited_binary_array = $pwa_columnar->re_transposition($transposited_binary_array,$user_key);

//echo "<h3>re_transposited_binary_array count =>  ".count($re_transposited_binary_array) ."</h3>";

$white_url = "images/white_photo.png";
$real_photo = imagecreatefrompng($white_url);
$real_photo_url = "images/real_photo.png";

# retranspiosited the array 
# add to real photo (create)
$c = 0 ;
for($x = 0 ; $x<800; $x++){
	$total = 800;
	$now = $x+1 ;
	$percent = (100 * $now ) / $total ;
	$pwa_otsu->send_to_browser(" ( ၃ /  ၃ ) စကားဝွက္ အသံုးျပဳၿပီး ပံုေဖာ္ျခင္း လုပ္ငန္း   ".$percent.  " %  ျပီးစီးေနပါျပီ");
	$pwa_otsu->update_progressbar($percent);
	
	for($y =0; $y<600; $y++){
		$current_index = ($x*800) + ($y );
		# get binary value
		$replace_value = $re_transposited_binary_array[$c];
		$c++;

		if($replace_value == 1) {
			$replace_value = 255 ;
		}

		// initialize new color
		$newColor = $replace_value  << 16 | $replace_value << 8 | $replace_value ;

		// set color at x , y on binary image
		imagesetpixel($real_photo, $x, $y, $newColor);
	}
}


//echo "debug array => ".json_encode($debug_array);
# write blank photo
# this photo will be retrieved binary (balck and white ) photo 
# write cover resource (may be it 'is stegano image ')
imagepng($real_photo,$real_photo_url);

echo "<br><h2> စကားဝွက္ အသံုးျပဳၿပီး ျပန္လည္ထုတ္ယူရရိွလာေသာ  အျဖဴအမည္း ဓာတ္ပံု</h2>";
echo "<img src='".$real_photo_url."'><br>";


$pwa_otsu->send_to_browser("ပံုေဖာ္ျခင္း လုပ္ငန္း ျပီးဆံုးပါျပီ ");

echo "<h3><a href='main.php'> မူလ စာမ်က္ႏွာသို႕ ျပန္သြားရန္</a></h3>";


?>

          </div>

    </div> <!-- /container -->

    
  </body>
</html>
