<?php


echo "welcome from IMAGE SERVER";
//echo "<br> we received ".$_POST['filename']." and ".$_POST['filedata'];
//list($type,$data) = explode(';', $_POST['filedata']);
//list($code,$real_png) = explode(',', $data);
ini_set('memory_limit','-1');

// we just need to handle  two image data 
// the first one is => telegraph
// the second is => cover

//list($first,$second) = explode(',',$_POST['filedata']);
//$pic = base64_decode($second);
//file_put_contents($_POST['filename'], $pic);

//$filename = $_POST['filename'];
$covername = "images/cover_photo.png";

// local image
$filename = "images/original_photo.png";

$user_key = "phyowaiaung63954";

echo "Original User Key => ".$user_key."<br>";

$stringParts = str_split($user_key);
sort($stringParts);
echo "Sorted user key => ".implode('', $stringParts). "<br>"; 




$secre_img = imagecreatefrompng($filename);
$cover_img = imagecreatefrompng($covername);
//$test = imagecreatefrompng("test.png");


// write telegraph to original photo 
imagepng($secre_img,'original_photo.png');

// covert to gray scale 
imagefilter($secre_img, IMG_FILTER_GRAYSCALE);
//imagepng($secre_img, 'secre_img_gray.png');

// write gray telegraph photo 
imagepng($secre_img,'gray_scale_photo.png');


$gray_secret = imagecreatefrompng('gray_scale_photo.png');


//$key_shifting = imagecreatefrompng('secre_img_gray.png');
$threshold_value = 127;


$str1 = "";
$str2="";
$black_and_white_string = "<table style='width:100%'>";
$original_binary_string ="";

for($x = 0 ; $x<800; $x++){
	$black_and_white_string .= "<tr>";
	for($y =0; $y<600; $y++){
			
					$gray_value = imagecolorat($gray_secret, $x, $y);
					$gray_decimal = ($gray_value >> 16) & 0xFF;
					if($gray_decimal > $threshold_value) $gray_decimal = 255;
					else $gray_decimal = 0 ;


					// initialize new color
					$newColor = $gray_decimal  << 16 | $gray_decimal << 8 | $gray_decimal ;

					// set color at x , y on image
					imagesetpixel($gray_secret, $x, $y, $newColor);



					$replace_value = 0;
					if($gray_decimal == 255 ) $replace_value = 1;
					else $replace_value = 0 ;

					$black_and_white_string .= "<td>".$replace_value."</td>";
					$original_binary_string .= $replace_value; 
					/*

					$rgb = imagecolorat($cover_img, $x, $y);
					$r = ($rgb >> 16) & 0xFF;
					$g = ($rgb >> 8) & 0xFF;
					$b = $rgb & 0xFF;

					$r_binary = decbin($r);
					substr_replace($r_binary, $replace_value, -1);

					$r = bindec($r_binary);

					$str1.= $replace_value;
					// initialize new color
					$a = $rgb & 0xFF000000;

					$newColor = $a | $r  << 16 | $g << 8 | $b ;

					// set color at x , y on image
					imagesetpixel($cover_img, $x, $y, $newColor);
					*/

	}
	$black_and_white_string .= "</tr>";
}

$black_and_white_string .= "</table>";


echo "<h2>Replace Value </h2>";
echo $black_and_white_string; 

// we need to know key length
$key_length = strlen($user_key);
echo "key length is ".$key_length . " and key is ". $user_key;


$d_table = array
  (
  array()
  );
$j = 0 ;
$row_number = 0; 

while($j < 480000){
	for($i=0; $i<$key_length; $i++){

		//echo " j is ".$j." and b n w values ". $original_binary_string[$j] . "<br>";
		
		$d_table[$row_number][$i] = $original_binary_string[$j];
		$j++;
		if($j == 480000){
			$i = $key_length;
		}
	}
	$row_number++;
}

// Open too see real column key structure
echo "<h2>our 2d table is </h2>".json_encode($d_table);

// we need to transform those 2d table rows
$transform_table = array
  (
  array()
  );

$changes = array();

$stringParts = str_split($user_key);
sort($stringParts);
$sorted_string = implode('', $stringParts);


for($i=0; $i<strlen($user_key); $i++){
	// we need to find from the first char
	$char = $user_key[$i];
	$char .= "";

	// find the new index from sorted string
	$position = strpos($sorted_string, $char);

	// replace with * char in sorted string
	$sorted_string = substr_replace($sorted_string,"*",$position,1);

	// add new changes value to changes array;
	//$changes[count($changes)]= array("old_index"=>$i,"new_index"=>$position);
	$changes[count($changes)] = $position;
}


$transformed_binary_string = "";
$loop_count=0; // for while loop count
for($k =0 ; $k<$row_number; $k++){
	// yeah, we insert data row by row
	for($n = 0; $n<$key_length; $n++){

		// which column we insert to new transform data table
		$new_index = $changes[$n] ;
		$transform_table[$k][$n] = $d_table[$k][$new_index]; 
		$transformed_binary_string .= $d_table[$k][$new_index];
		$loop_count++;
		if($loop_count == 480000){
			$n = $key_length;
			// just complete inner loop
		}
	}
}
// Open to see real transformed colunm data
echo "<h2>New Transorm Table is </h2>".json_encode($transform_table);


// now, we have to add those new transform table data 480000 to cover image 800*600 pixel
$transformed_binary_string_index = 0;
// next 0 1 table with columan transformation values
$columnar_transformation_table = "<table>";
for($x = 0 ; $x<800; $x++){
	$columnar_transformation_table .= "<tr>";
	for($y =0; $y<600; $y++){
					/*
					$gray_value = imagecolorat($gray_secret, $x, $y);
					$gray_decimal = ($gray_value >> 16) & 0xFF;
					if($gray_decimal > $threshold_value) $gray_decimal = 255;
					else $gray_decimal = 0 ;


					// initialize new color
					$newColor = $gray_decimal  << 16 | $gray_decimal << 8 | $gray_decimal ;

					// set color at x , y on image
					imagesetpixel($gray_secret, $x, $y, $newColor);



					$replace_value = 0;
					if($gray_decimal == 255 ) $replace_value = 1;
					else $replace_value = 0 ;

					$black_and_white_string .= "<td>".$replace_value."</td>";
					$original_binary_string .= $replace_value; 

					*/
					$replace_value = 0;
					$replace_value = $transformed_binary_string[$transformed_binary_string_index];
					$transformed_binary_string_index++;
					

					$columnar_transformation_table .= "<td>".$replace_value."</td>";

					$rgb = imagecolorat($cover_img, $x, $y);
					$r = ($rgb >> 16) & 0xFF;
					$g = ($rgb >> 8) & 0xFF;
					$b = $rgb & 0xFF;

					$r_binary = decbin($r);
					substr_replace($r_binary, $replace_value, -1);

					$r = bindec($r_binary);

					//$str1.= $replace_value;
					// initialize new color
					$a = $rgb & 0xFF000000;

					$newColor = $a | $r  << 16 | $g << 8 | $b ;

					// set color at x , y on image
					imagesetpixel($cover_img, $x, $y, $newColor);
					

	}
	$columnar_transformation_table .= "</tr>";
}

$columnar_transformation_table .= "</table>";

echo "<h2> Columanr Transformation  </h2>";
echo $columnar_transformation_table;



















  /*
}
$k=0; // for while loop count

while($k < 480000){
	// yeah we need to loop 480000 times :D :D :D

}
*/

			
//imagepng($gray_secret, 'telegraph_binary.png');

// write binary image
imagepng($gray_secret,'binary_photo.png');

// write stegano photo
imagepng($cover_img,'stegano_photo.png');


//imagepng($cover_img, 'stegano_img.png');
//imagepng($test, 'hide in plane.png');
//imagepng($key_shifting, 'shifting_img.png');



/*
$cypher_img = imagecreatefrompng('stegano_img.png');
$secret_img = imagecreatefrompng('stegano_img.png');

$test = imagecreatefrompng("test.png");

for($x = 0 ; $x<800; $x++){
	for($y =0; $y<600; $y++){

					$rgb = imagecolorat($cypher_img, $x, $y);
					$r = ($rgb >> 16) & 0xFF;
					$g = ($rgb >> 8) & 0xFF;
					$b = $rgb & 0xFF;

					$r_binary = decbin($r);
					$last = substr($r_binary, -1);
					$str2.=$last;

					$new = 0;
					if($last == 1) $new = 255;
					else $new = 0;

					// initialize new color
					// initialize new color
					$a = $rgb & 0xFF000000;
					$newColor = $a | $new  << 16 | $new << 8 | $new ;

					// set color at x , y on image
					imagesetpixel($test, $x, $y, $newColor);



	}

}



imagepng($test, 'reallllll.png');

$sts = strcmp($str1,$str2);

echo "image is stored as ".$_POST['filename']." key binary is ".$key_binary. "key length is ".$key_length." stattus is ".$sts." and len ".strlen($str1)." and ".strlen($str2);
*/
// we have to show 
// 1. original photo
// 2. gray scale photo
// 3. binary photo
// 4. cover photo
// 5. stegano photo
echo "<br><h2>Original Image</h2>";
echo "<img src='original_photo.png'><br>";

echo "<br><h2>Gray Scale Image</h2>";
echo "<img src='gray_scale_photo.png'><br>";

echo "<br><h2>Binary Image</h2>";
echo "<img src='binary_photo.png'><br>";

echo "<br><h2>Cover Image</h2>";
echo "<img src='cover.png'><br>";

echo "<br><h2>Stegano Image</h2>";
echo "<img src='stegano_photo.png'><br>";



					
					/*


$key = $_POST['secret_key'];

$key_binary = decbin($key);
$key_length = strlen($key_binary);
$bool = true;
$key_start = 0 ;
					$result_value = $replace_value + $key_binary[$key_start];
					if($result_value == 2 ) $result_value = 0;
					if($bool = true) $key_start++;
					else $key_start--;


					if($key_start  == $key_length) {
						$key_start = $key_length - 1;
						$bool = false;
					}
					if($key_start < 0 ){
						$key_start = 0;
						$bool = ture;
					}
					$pixel = 0;
					if($result_value == 0 )  $pixel = 0;
					if($result_value == 1 ) $pixel = 255;

					
					// initialize new color
					$newColor = $pixel  << 16 | $pixel << 8 | $pixel ;
					// set color at x , y on image
					imagesetpixel($key_shifting, $x, $y, $newColor);
*/


?>