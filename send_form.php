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

// Getting original photo, cover photo and password;
$original_photo_data = isset($_POST['original_photo_data']) ? $_POST['original_photo_data'] : null;
$cover_photo_data =  isset($_POST['cover_photo_data']) ? $_POST['cover_photo_data'] : null;
$password = isset($_POST['password']) ? $_POST['password'] : null;
if($original_photo_data == null || $cover_photo_data == null || $password == null){
	die("original photo, cover photo and password have to be provided");
}

# Defining Photo URL
$milliseconds = round(microtime(true) * 1000);
$random_no = rand(0,100000);
$image_filename = $random_no.$milliseconds; # ender with random number 
$original_photo_temp_url = "temp_img/".$image_filename;
$cover_photo_temp_url = "temp_img/".$image_filename."_cover";

# photo resources
$original_photo = false; // image resource
$cover_photo = false; // image resource
$gray_photo = false; // image resource
$stegano_photo = false; // image resource

# width and height
$default_width = null;
$default_height = null;

# get file name and server cover name url
$original_photo_url = "images/".$image_filename."original.png";
$cover_photo_url = "images/".$image_filename."cover_photo.png";
$gray_photo_url = "images/".$image_filename."gray_photo.png";
$stegano_photo_url = "images/".$image_filename.".png";


$server_key = "whatisthislifeiffullofcare0wehavenotime0tostandandstarewhatisthislifeiffullofcare0wehavenotime0tostandandstarewhatisthislifeiffullofcare0wehavenotime0tostandandstarewhatisthislifeiffullofcare0wehavenotime0tostandandstarewhatisthislifeiffullofcare0wehavenotime0tostandandstarewhatisthislifeiffullofcare0wehavenotime0tostandandstarewhatisthislifeiffullofcare0wehavenotime0tostandandstarewhatisthis34256";
$user_key = $password.$server_key;

$gray_one_d_array = array();


# writing to temp directory
list($first,$second) = explode(',',$_POST['original_photo_data']);
list($data,$encoding) = explode(';', $first);
list($data,$type_pair) = explode(':', $data);
list($media,$media_type) = explode('/',$type_pair);
$pic = base64_decode($second);
file_put_contents($original_photo_temp_url, $pic); # writing photo data to actual file 
if($media_type != 'png' && $media_type != 'jpeg'){
	die("we only support png and jpeg for original photo, now it is ".$media_type);
}
# reading photo file as php image resource
if($media_type == 'png') $original_photo = imagecreatefrompng($original_photo_temp_url) ; 
if($media_type == 'jpeg') $original_photo = imagecreatefromjpeg($original_photo_temp_url) ; 
list($width, $height, $type, $attr) = getimagesize($original_photo_temp_url);
$default_width = $width;
$default_height = $height;
# write original_photo to specific folder 
imagepng($original_photo,$original_photo_url);
# apply original photo GRAY filter
imagefilter($original_photo, IMG_FILTER_GRAYSCALE);
# write gray photo to specific folder
imagepng($original_photo, $gray_photo_url);
# read gray photo resource
$gray_photo = imagecreatefrompng($gray_photo_url);

// echo "<br><h2>မူလ ဓာတ်ပုံ</h2>";
// echo "<img src='".$original_photo_url."'><br>";

// echo "<br><h2>Gary  ဓာတ်ပုံ</h2>";
// echo "<img src='".$gray_photo_url."'><br>";




# writing cover photo to temp directory
list($first,$second) = explode(',',$cover_photo_data);
list($data,$encoding) = explode(';', $first);
list($data,$type_pair) = explode(':', $data);
list($media,$media_type) = explode('/',$type_pair);
$pic = base64_decode($second);
file_put_contents($cover_photo_temp_url, $pic); # write user post data with user title on root folder
if($media_type != 'png' && $media_type != 'jpeg'){
	die("we only support png and jpeg for cover  photo, now it is ".$media_type);
}
# reading photo file as php image resource
if($media_type == 'png') $cover_photo = imagecreatefrompng($cover_photo_temp_url) ; 
if($media_type == 'jpeg') $cover_photo = imagecreatefromjpeg($cover_photo_temp_url) ; 
list($width, $height, $type, $attr) = getimagesize($cover_photo_temp_url);
# resizing to original photo size
$cover_photo = imagescale($cover_photo,$default_width,$default_height,IMG_NEAREST_NEIGHBOUR);
# write cover_photo to specific folder 
$stegano_photo = $cover_photo; # copy image resource
imagepng($cover_photo,$cover_photo_url); # write image resource to file path

// echo "<br><h2>ကာဘာ ဓာတ်ပုံ</h2>";
// echo "<img src='".$cover_photo_url."'><br>";



# make 8 digit binary staring
function make_eight_bit($binary_string){
	$add_word_count = 8 - strlen($binary_string);
	$add_word = "";
	for($i = 0 ; $i <$add_word_count ; $i++){
		$add_word .= "0";
	}
	$binary_string = $add_word.$binary_string;	
	return $binary_string;
}



require('pwa_otsu.php');
$pwa_otsu = new Pwa_otsu($gray_photo_url);



require('pwa_columnar.php');
$pwa_columnar = new Pwa_columnar();


// getting one dimensional  gary value array 
for($x = 0 ; $x<$default_width; $x++){
	//$total = 800;
	$now = $x+1 ;
	$percent = (100 * $now ) / $default_width ;
	$pwa_otsu->send_to_browser("ကြေးနန်း ပုံကို စီစစ်ခြင်းလုပ်ငန်း  ".$percent.  " % ပြီးစီးနေပါပြီ။");
	$pwa_otsu->update_progressbar($percent);
	for($y =0; $y<$default_height; $y++){
		$gray_value = imagecolorat($gray_photo, $x, $y); // Getting RGB pair
		$gray_decimal = ($gray_value >> 16) & 0xFF; // getting R value ; Shifting
		$gray_one_d_array[] = $gray_decimal ;
	}
}


$pwa_otsu->send_to_browser("Binary Transposition လုပ်ငန်း စတင်နေပါပြီ");
$pwa_otsu->update_progressbar(10);
$gray_transposited_one_d_array = $pwa_columnar->transposition($gray_one_d_array,$user_key);


$c = 0 ;
for($x = 0 ; $x<$default_width; $x++){
	$now = $x+1 ;
	$percent = (100 * $now ) / $default_width ;
	$pwa_otsu->send_to_browser("ပုံဖျတ်ခြင်း လုပ်ငန်း  : ".$percent.  " % ပြီးစီးနေပါပြီ။");
	$pwa_otsu->update_progressbar($percent);
	for($y =0; $y<$default_height; $y++){
		# getting gray decimal
		$gray_decimal = $gray_transposited_one_d_array[$c];
		# change decimal to binary
		$gray_binary = decbin($gray_decimal);
		# make 8 digit binary staring
		$gray_binary = make_eight_bit($gray_binary);
		# sub three phase
		$red_three = substr($gray_binary,0,3);
		$green_three = substr($gray_binary,3,3);
		$blue_two = substr($gray_binary,6,2);

		$rgb = imagecolorat($cover_photo, $x, $y);
		$r = ($rgb >> 16) & 0xFF; // Get R value 0-255
		$g = ($rgb >> 8) & 0xFF; // Get G value 0-255
		$b = $rgb & 0xFF; // Get B value 0-255

		$r_binary = decbin($r); 
		$r_binary = make_eight_bit($r_binary); 
		$r_msb_5 = substr($r_binary,0,5) ;
		$r_8_bit = $r_msb_5.$red_three; 
		$r = bindec($r_8_bit); 

		$g_binary = decbin($g); 
		$g_binary = make_eight_bit($g_binary); 
		$g_msb_5 = substr($g_binary,0,5) ;
		$g_8_bit = $g_msb_5.$green_three; 
		$g = bindec($g_8_bit); 

		$b_binary = decbin($b); 
		$b_binary = make_eight_bit($b_binary); 
		$b_msb_6 = substr($b_binary,0,6) ;
		$b_8_bit = $b_msb_6.$blue_two; 
		$b = bindec($b_8_bit); 

		$a = $rgb & 0xFF000000;

		$newColor = $a | $r  << 16 | $g << 8 | $b ;
		// set color at x , y on image
		imagesetpixel($stegano_photo, $x, $y, $newColor);
	}
}

imagepng($stegano_photo,$stegano_photo_url); # write image resource to file path


//echo "<br><h2>Steganograpy Image </h2>";
//echo "<img src='".$stegano_photo_url."'><br>";



$pwa_otsu->send_to_browser(" ပုံဖျတ်ခြင်း နှင့် ပုံဝှက်ခြင်း လုပ်ငန်း ပြီးပါပြီ ");


# Defining Photo URL
$file_to_delete = array($original_photo_temp_url,$cover_photo_temp_url,$original_photo_url,$cover_photo_url,$gray_photo_url);
for($i = 0 ; $i<count($file_to_delete); $i++){
	if (!unlink($file_to_delete[$i])) {  
		//echo ("$file_to_delete[$i] cannot be deleted due to an error");  
	 }else {  
		//echo ("$file_to_delete[$i] has been deleted");  
		//echo "<br>";
	} 
}
//$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$shareable_link = "http://localhost/image_share/image.php?v=".$image_filename;
//$shareable_link = "http://localhost/image_share/".$stegano_photo_url;

echo "<h3>Shareable url is <a href='$shareable_link'>".$shareable_link."</a></h3>";


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
