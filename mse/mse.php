<?php
echo "Welcome to MSE";

list($first,$second) = explode(',',$_POST['cover_photo_data']);
list($data,$encoding) = explode(';', $first);
list($data,$type_pair) = explode(':', $data);
list($media,$media_type) = explode('/',$type_pair);
$pic = base64_decode($second);
$cover_photo_data_url = "temp_img/cover_photo";
file_put_contents($cover_photo_data_url, $pic);
$cover_photo = false;
if($media_type != 'png' && $media_type != 'jpeg'){
	die("we only support png and jpeg for cover photo");
}
if($media_type == 'png') $cover_photo = imagecreatefrompng($cover_photo_data_url) ;
if($media_type == 'jpeg') $cover_photo = imagecreatefromjpeg($cover_photo_data_url) ;



list($first,$second) = explode(',',$_POST['stegano_photo_data']);
list($data,$encoding) = explode(';', $first);
list($data,$type_pair) = explode(':', $data);
list($media,$media_type) = explode('/',$type_pair);
$pic = base64_decode($second);
$stegano_photo_data_url = "temp_img/stegano_photo";
file_put_contents($stegano_photo_data_url, $pic);
$stegano_photo = false;
if($media_type != 'png' && $media_type != 'jpeg'){
	die("we only support png and jpeg ");
}
if($media_type == 'png') $stegano_photo = imagecreatefrompng($stegano_photo_data_url) ;
if($media_type == 'jpeg') $stegano_photo = imagecreatefromjpeg($stegano_photo_data_url) ;

$original_url = "original.png";
$stegano_url = "stegano.png";

imagepng($cover_photo,$original_url);
imagepng($stegano_photo,$stegano_url);


echo "<br><h2>မူလ ဓာတ္ပံု</h2>";
echo "<img style='width:400px' src='".$original_url."'><br>";

echo "<br><h2>ေျပာင္းလဲထားေသာ ဓာတ္ပံု</h2>";
echo "<img style='width:400px' src='".$stegano_url."'><br>";

// get width and height of two photo and compare
list($original_width, $original_height, $original_type, $original_attr) = getimagesize($original_url);
list($stegano_width, $stegano_height, $stegano_type, $stegano_attr) = getimagesize($original_url);

if($original_width != $stegano_width || $original_height != $stegano_height){
	die( " Two photo do not have same width and height ");
}

$sum_of_diff = 0 ;

for($x = 0 ; $x<$original_width; $x++){
	for($y =0; $y<$original_height; $y++){
		$original_rgb = imagecolorat($cover_photo, $x, $y);
		$original_r = ($original_rgb >> 16) & 0xFF;
		$original_g = ($original_rgb >> 8) & 0xFF;
		$original_b = $original_rgb & 0xFF;

		$stegano_rgb = imagecolorat($stegano_photo, $x, $y);
		$stegano_r = ($stegano_rgb >> 16) & 0xFF;
		$stegano_g = ($stegano_rgb >> 8) & 0xFF;
		$stegano_b = $stegano_rgb & 0xFF;

		$r_abs = abs($original_r - $stegano_r);
		$g_abs = abs($original_g - $stegano_g);
		$b_abs = abs($original_b - $stegano_b);

		$sum_abs = $r_abs + $g_abs + $b_abs ;
		$sum_of_diff = $sum_of_diff + $sum_abs;
	}
}

// we get 3 channel
//echo "sum_of_diff is ".$sum_of_diff ."<br>";
$sum_of_diff = $sum_of_diff / 3 ;

// pixel count
$pixel_count = $original_width * $original_height ;
echo "pixel_count is ".$pixel_count."<br>";

$sum_of_diff = $sum_of_diff / $pixel_count ; 
//echo "divided ".$sum_of_diff."<br>";


// 0 to 255 values
$sum_of_diff = $sum_of_diff / 255 ;
echo "<h2> MSE is ".$sum_of_diff ."</h2>";
?>