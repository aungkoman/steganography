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


    <title><?php echo $_SESSION['unit_id']; ?> ကြေးန်း ပို့ခြင်း </title>

  </head>

  <body>

    <div class="container">


<?php
  if(isset($_GET['msg'])){
      echo '<div class="alert alert-info">';
      echo '<strong>သတင်းအချက်အလက်  : </strong> ';
      echo $_GET['msg'];
      echo '</div>';
  }
?>


      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <h2>ကြေးန်းစာ ပေးပို့ခြင်း စနစ်</h2>
        <h3> ပုံ ဝှက်ခြင်း လုပ်ငန်း</h3>
  	<p>လက်ရှိ အခြေအနေ :: <span id="progress_status"></span></p> 
  	<div class="progress" id="progress_bar_div">
    		<div class="progress-bar progress-bar-striped progress-bar-animated"  id="progress_bar" style="width:10%"></div>
  	</div>



<?php
// set miximum executin limit in second
// in this case 5 minutes
set_time_limit(300);
ini_set('memory_limit','-1');


list($first,$second) = explode(',',$_POST['original_photo_data']);
list($data,$encoding) = explode(';', $first);
list($data,$type_pair) = explode(':', $data);
list($media,$media_type) = explode('/',$type_pair);
$pic = base64_decode($second);
# write user post data with user title on root folder
// 
$title = "title";
$photo_data_url = "temp_img/".$title;
file_put_contents($photo_data_url, $pic);

$original_photo = false;
if($media_type != 'png' && $media_type != 'jpeg'){
	die("we only support png and jpeg for original photo");
}


if($media_type == 'png') $original_photo = imagecreatefrompng($photo_data_url) ;
if($media_type == 'jpeg') $original_photo = imagecreatefromjpeg($photo_data_url) ;

# resize cover photo to 800 * 600 
$original_photo = imagescale($original_photo,800,600,IMG_NEAREST_NEIGHBOUR);

list($first,$second) = explode(',',$_POST['cover_photo_data']);
list($data,$encoding) = explode(';', $first);
list($data,$type_pair) = explode(':', $data);
list($media,$media_type) = explode('/',$type_pair);
$pic = base64_decode($second);
# write user post data with user title on root folder
$cover_photo_data_url = "temp_img/".$title."_cover";
file_put_contents($cover_photo_data_url, $pic);

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



//echo "<h2> hello 8 bit </h2> <br>" . make_eight_bit("1101");

//echo "<hr><hr>";

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



# read original photo data and cover photo url
//$original_photo = imagecreatefrompng($photo_data_url);
//$cover_photo = imagecreatefrompng($cover_url);
# we need to read jpg or png file type 
# check user file type 
//$cover_type = pathinfo($cover_photo_data_url,PATHINFO_EXTENSION);
//echo "<h2>Cover Photo Type is ".$cover_type."</h2>";


$cover_photo = false;
if($media_type != 'png' && $media_type != 'jpeg'){
	die("we only support png and jpeg for cover photo");
}


if($media_type == 'png') $cover_photo = imagecreatefrompng($cover_photo_data_url) ;
if($media_type == 'jpeg') $cover_photo = imagecreatefromjpeg($cover_photo_data_url) ;

# resize cover photo to 800 * 600 
$cover_photo = imagescale($cover_photo,800,600,IMG_NEAREST_NEIGHBOUR);
$binary_cover_photo = $cover_photo;



# write original_photo to specific folder 
imagepng($original_photo,$original_url);
# write cover_photo to specific folder 
imagepng($cover_photo,$cover_url);

# apply original photo GRAY filter
imagefilter($original_photo, IMG_FILTER_GRAYSCALE);
# write gray photo to specific folder
imagepng($original_photo, $gray_url);


# read gray photo resource
$gray_photo = imagecreatefrompng($gray_url);




echo "<br><h2>မူလ ဓာတ်ပုံ</h2>";
echo "<img src='".$original_url."'><br>";



echo "<br><h2>ကာဘာ ဓာတ်ပုံ</h2>";
echo "<img src='".$cover_url."'><br>";



require('pwa_otsu.php');
$pwa_otsu = new Pwa_otsu($gray_url);
$value = $pwa_otsu->otus_threshold();

require('pwa_columnar.php');
$pwa_columnar = new Pwa_columnar();


$threshold_value = $value;
//$threshold_value = 127;



$password = $_POST['password'];
// now we have user key (password)
// we need one dimentsional array for binary and grayscale value 
// ok two array we need
$server_key = "whatisthislifeiffullofcare0wehavenotime0tostandandstarewhatisthislifeiffullofcare0wehavenotime0tostandandstarewhatisthislifeiffullofcare0wehavenotime0tostandandstarewhatisthislifeiffullofcare0wehavenotime0tostandandstarewhatisthislifeiffullofcare0wehavenotime0tostandandstarewhatisthislifeiffullofcare0wehavenotime0tostandandstarewhatisthislifeiffullofcare0wehavenotime0tostandandstarewhatisthis34256";
$user_key = $password.$server_key;
$binary_one_d_array = array();
$gray_one_d_array = array();

# read blank photo to make binary photo
$white_url = "images/white_photo.png";
$binary_photo = imagecreatefrompng($white_url);
// getting one dimensional array 
$c = 0;
for($x = 0 ; $x<800; $x++){
	$total = 800;
	$now = $x+1 ;
	$percent = (100 * $now ) / $total ;
	$pwa_otsu->send_to_browser("ကြေးနန်း ပုံကို စီစစ်ခြင်းလုပ်ငန်း  ".$percent.  " % ပြီးစီးနေပါပြီ။");
	$pwa_otsu->update_progressbar($percent);
	for($y =0; $y<600; $y++){
		$gray_value = imagecolorat($gray_photo, $x, $y);
		$gray_decimal = ($gray_value >> 16) & 0xFF; // getting R value

		$gray_one_d_array[count($gray_one_d_array)] = $gray_decimal ;

		if($gray_decimal > $threshold_value) $gray_decimal = 255;
		else $gray_decimal = 0 ;

		$replace_value = 0;
		if($gray_decimal == 255 ) $replace_value = 1;

		$binary_one_d_array[$c] = $replace_value;	
		$c++;


		// initialize new color
		$newColor = $gray_decimal  << 16 | $gray_decimal << 8 | $gray_decimal ;
		// set color at x , y on binary image
		imagesetpixel($binary_photo, $x, $y, $newColor);
				
	}
}


# write binary photo (actual photo ) to specific folder 
imagepng($binary_photo,$binary_url);
# Show TIME
echo "<br><h2>အဖြူ အမည်း ဓာတ်ပုံ </h2>";
echo "<img src='".$binary_url."'><br>";




$pwa_otsu->send_to_browser("Binary Transposition လုပ်ငန်း စတင်နေပါပြီ");
$pwa_otsu->update_progressbar(10);
echo "<script> console.log('binary one day transpostion start '); </script>";
$binary_transposited_one_d_array = $pwa_columnar->transposition($binary_one_d_array,$user_key);
echo "<script> console.log(' binary_transposited_one_d_array ".json_encode($binary_transposited_one_d_array)." '); </script>";


# we have to show transposited array in photo black and white 
# read blank photo to make binary photo
$white_url = "images/white_photo.png";
$transposited_binary_photo = imagecreatefrompng($white_url);
$transposited_binary_photo_url = "images/transposited_binary_photo.png";
$c = 0 ;
for($x = 0 ; $x<800; $x++){
	$total = 800;
	$now = $x+1 ;
	$percent = (100 * $now ) / $total ;
	$pwa_otsu->send_to_browser("ပုံဖျတ်ခြင်း လုပ်ငန်း  : ".$percent.  " % ပြီးစီးနေပါပြီ။");
	$pwa_otsu->update_progressbar($percent);
	for($y =0; $y<600; $y++){
					# this section use columna transpositin array
					# we do not use current pixel value
					# instead, we just use columna array element 
					# m i ok ???
					# caclculate current index
					
					# get binary value
					$replace_value = $binary_transposited_one_d_array[$c];
					$c++;
					
					# set current pixel with replace value 
					$gray_decimal = 0 ;
					if($replace_value == 1 ) $gray_decimal = 255 ;
					// initialize new color
					$newColor = $gray_decimal  << 16 | $gray_decimal << 8 | $gray_decimal ;

					// set color at x , y on binary image
					imagesetpixel($transposited_binary_photo, $x, $y, $newColor);


					$rgb = imagecolorat($binary_cover_photo, $x, $y);
					$r = ($rgb >> 16) & 0xFF; // Get R value 0-255
					$g = ($rgb >> 8) & 0xFF; // Get G value 0-255
					$b = $rgb & 0xFF; // Get B value 0-255

					$r_binary = decbin($r); // Decimal to Binary 
					$r_binary = make_eight_bit($r_binary); // Making 8 digit binary , I mean 101 to 00000101 .May be it convert to string
					//substr_replace($r_binary, $replace_value, -1);

					$r_msb_7 = substr($r_binary,0,7) ;  // Substract required binary path , this mean we need to keep the first 7 digit from R binary staring
					$r_8_bit = $r_msb_7.$replace_value; // Add stegano binary string and make 8 digit again

					$r = bindec($r_8_bit); // From binary to Decimal
					// initialize new color
					$a = $rgb & 0xFF000000;

					$newColor = $a | $r  << 16 | $g << 8 | $b ;

					// set color at x , y on image
					imagesetpixel($binary_cover_photo, $x, $y, $newColor);


	}
}




# write binary photo (actual photo ) to specific folder 
imagepng($transposited_binary_photo,$transposited_binary_photo_url);
echo "<br><h2>ပုံဖျတ်ထားသော အဖြူအမည်း ကြေးနန်းဓာတ်ပုံ </h2>";
echo "<img src='".$transposited_binary_photo_url."'><br>";



imagepng($binary_cover_photo,$binary_stegano_url);
# Show TIME
echo "<br><h2>ပုံဖျတ်ထားသော အဖြူအမည်း ကြေးနန်းဓာတ်ပုံ ထည့်ဝှက်ထားသော ကာဘာ ဓာတ်ပု</h2>";
echo "<img src='".$binary_stegano_url."'><br>";


$pwa_otsu->send_to_browser(" ပုံဖျတ်ခြင်း နှင့် ပုံဝှက်ခြင်း လုပ်ငန်း ပြီးပါပြီ ");
####################################################################################



# we have to read the stegano photo file content 
# encode to base64 binary to string 
# save to database 
//$path ='stegano_photo.png';
//$type = pathinfo($binary_stegano_url,PATHINFO_EXTENSION);
//$data = file_get_contents($binary_stegano_url);
//$filedata = 'data:image/'.$type.';base64,'.base64_encode($data);

// we need to insert to those telegraph table
//$sql="INSERT INTO telegraphs (sender,receiver,title,picture_data,dto) VALUES ('$sender','$receiver','$title','$filedata','$dto')";

	// $result=$conn->query($sql);
	// if($result)
	// {
	// 	echo "";
	// 	echo '<div class="alert alert-info">';
      	// 	echo '<strong>သတင်းအချက်အလက်  : </strong> ';
      	// 	echo "ကြေးနန်းဓာတ်ပုံ ကို ပေးပို့ပြီးပါပြီ "; 
      	// 	echo '</div>';

	// 	echo "<h3><a href='main.php'> မူလစာမျက်နှာသို့ ပြန်သွားရန်</a></h3>";
	// 	//header("location:main.php?msg=Message was sent to $receiver!");
	// }
	// else
	// {
	// 	die( "Database insert  Error.".$conn->error);
	// }

				
?>

          </div>

    </div> <!-- /container -->

    
  </body>
</html>
