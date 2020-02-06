			
// gray image decoding
# read blank photo
$white_url = "images/white_photo.png";
$white_photo = imagecreatefrompng($white_url);

# read stegano photo
$stegano_photo = imagecreatefrompng($photo_data_url);

for($x = 0 ; $x<800; $x++){
	$total = 800;
	$now = $x+1 ;
	$percent = (100 * $now ) / $total ;
	$pwa_otsu->send_to_browser("ပံုေဖာ္ျခင္း လုပ္ငန္း ".$percent.  " %  ျပီးစီးေနပါျပီ");
	$pwa_otsu->update_progressbar($percent);
	
	for($y =0; $y<600; $y++){
		# read stegano image pixel value and extract r,g,b decimal
		$rgb = imagecolorat($stegano_photo, $x, $y);
		$r = ($rgb >> 16) & 0xFF;
		$g = ($rgb >> 8) & 0xFF;
		$b = $rgb & 0xFF;
		# change to binary
		$r_binary = decbin($r);
		$g_binary = decbin($g);
		$b_binary = decbin($b);
		# make 8 bit binary string
		$r_binary = make_eight_bit($r_binary);
		$g_binary = make_eight_bit($g_binary);
		$b_binary = make_eight_bit($b_binary);
		# extract 3-3-2 binary string from r,g,b respectively
		$r_replace_value = substr($r_binary,5,3) ;
		$g_replace_value = substr($g_binary,5,3) ;
		$b_replace_value = substr($b_binary,6,2) ;
		# make new 8 bit binary string
		$new_binary_string = $r_replace_value.$g_replace_value.$b_replace_value ;
		$new_decimal = bindec($new_binary_string);
		// initialize new color
		$a = $rgb & 0xFF000000;
		$newColor = $a | $new_decimal  << 16 | $new_decimal << 8 | $new_decimal ;
		// set color at x , y on image
		imagesetpixel($white_photo, $x, $y, $newColor);
	}
}

# write the retrieved photo
imagepng($white_photo,$retrieve_url);


echo "<br><h2>ျပန္လည္ရရိွေသာ Grayscale ဓာတ္ပံု</h2>";
echo "<img src='".$retrieve_url."'><br>";




$binary_re_transposited_one_d_array = $pwa_columnar->re_transposition($binary_transposited_one_d_array,$user_key);
echo "<script> console.log(' binary_re_transposited_one_d_array ".json_encode($binary_re_transposited_one_d_array)." '); </script>";


$pwa_otsu->send_to_browser("Gray Transposition လုပ္ငန္း စတင္ေနပါျပီီ");
$pwa_otsu->update_progressbar(40);

echo "<script> console.log(' gray_one_d_array ".json_encode($gray_one_d_array)." '); </script>";
echo "<script> console.log('gray one day transpostion start '); </script>";
$gray_transposited_one_d_array = $pwa_columnar->transposition($gray_one_d_array,$user_key);
echo "<script> console.log(' gray_transposited_one_d_array ".json_encode($gray_transposited_one_d_array)." '); </script>";
echo "<script> console.log('gray one day transpostion finished '); </script>";


echo "<script> console.log('gray re transposited start '); </script>";
$gray_re_transposited_one_d_array = $pwa_columnar->re_transposition($gray_transposited_one_d_array,$user_key);
echo "<script> console.log(' gray_re_transposited_one_d_array ".json_encode($gray_re_transposited_one_d_array)." '); </script>";

// binary image
# in this section we have to use 
# 800, 600 inner loop
# the gray photo to read pixel value
# blank photo (white in this example ) to produce pure binary image by thresholding above gray photo pixel value
# this blank photo is only required to show the sender that their photo is transformed to those black and white photo version :P 
# now we have to show actual stegano image 
# thus, cover photo to change (hide) our 0-1 , in this case they are $binary_cover_photo 
# this will in turn be binary stegano image and we will save this to database :D :D :D

// adding one dimensional array to cover image 
// adding binary code to cover image

for($x = 0 ; $x<800; $x++){
	$total = 800;
	$now = $x+1 ;
	$percent = (100 * $now ) / $total ;
	$pwa_otsu->send_to_browser("အျဖဴအမည္း ဓာတ္ပံုေျပာင္းလဲျခင္း  ".$percent.  " % ၿပီးစီးေနပါျပီ။");
	$pwa_otsu->update_progressbar($percent);
	for($y =0; $y<600; $y++){
		/* we do not need to read pixel */
		/* just read current index of transported array */
		/* here we start for bianry code  */
					
					/* to show uer b n w photo */
					$gray_value = imagecolorat($gray_photo, $x, $y);
					$gray_decimal = ($gray_value >> 16) & 0xFF;
		
					if($gray_decimal > $threshold_value) $gray_decimal = 255;
					else $gray_decimal = 0 ;

					// initialize new color
					$newColor = $gray_decimal  << 16 | $gray_decimal << 8 | $gray_decimal ;

					// set color at x , y on binary image
					imagesetpixel($binary_photo, $x, $y, $newColor);

					# this section is used for normal encryption
					# we use while columna transposition is not availabe
					
					$replace_value = 0;
					if($gray_decimal == 255 ) $replace_value = 1;
					
					
					# this section use columna transpositin
					# we do not use current pixel value
					# instead, we just use columna array element 
					# m i ok ???
					# caclculate current index
					/*
					$current_index = ($x+1) * ($y + 1 );
					if($current_index == 480000) $current_index = 0;
					# get binary value
					$replace_value = $binary_transposited_one_d_array[$current_index];
					*/

					$rgb = imagecolorat($binary_cover_photo, $x, $y);
					$r = ($rgb >> 16) & 0xFF;
					$g = ($rgb >> 8) & 0xFF;
					$b = $rgb & 0xFF;

					$r_binary = decbin($r);
					$r_binary = make_eight_bit($r_binary);
					//substr_replace($r_binary, $replace_value, -1);

					$r_msb_7 = substr($r_binary,0,7) ;
					$r_8_bit = $r_msb_7.$replace_value;

					$r = bindec($r_8_bit);
					// initialize new color
					$a = $rgb & 0xFF000000;

					$newColor = $a | $r  << 16 | $g << 8 | $b ;

					// set color at x , y on image
					imagesetpixel($binary_cover_photo, $x, $y, $newColor);
	}
}

# so , we have binary image and binary cover image 

# write binary photo (actual photo ) to specific folder 
imagepng($binary_photo,$binary_url);
# write binary stegano photo  to specific folder 
# now binary cover photo contain our 0 - 1 cipher code from above loop 
imagepng($binary_cover_photo,$binary_stegano_url);

# Show TIME

echo "<br><h2>အျဖဴ အမည္း ဓာတ္ပံု </h2>";
echo "<img src='".$binary_url."'><br>";

echo "<br><h2>အျဖဴ အမည္း ထည့္ဝွက္ထားေသာ ပံု </h2>";
echo "<img src='".$binary_stegano_url."'><br>";





# here is STEGANO GRAY Section 

# binary stegano decoding process
# read blank photo
# read blank photo to make binary photo
$white_url = "images/white_photo.png";
$binary_photo = imagecreatefrompng($white_url);

# read binary stegano photo 
$binary_stegano_photo = imagecreatefrompng($binary_stegano_url);


# decoding transpoted stegano photo
# so, we need  one dimensitional array of transported element
# and we create 2 d table using sorted user key
# then we consturct the normal table by using normal user key 
# finally we create one dimensition array to decrypt 
$decrypt_binary_one_d_array = array();
for($x = 0 ; $x<800; $x++){
	$total = 800;
	$now = $x+1 ;
	$percent = (100 * $now ) / $total ;
	$pwa_otsu->send_to_browser(" decryption , columna transpostion , making one dimensitional array ".$percent.  " %  ျပီးစီးေနပါျပီ");
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

		# if 1 , change to 255
		# if 0 , will be 0 :D :D :D
		//if($r_replace_value == 1) {
		//	$r_replace_value = 255 ;
		//}

		// making one dimensional binary array ( may be sorted 2 d table )
		$decrypt_binary_one_d_array[count($decrypt_binary_one_d_array)] = $r_replace_value ;
	}
}


$pwa_otsu->send_to_browser("Binary Decryption  လုပ္ငန္း စတင္ေနပါျပီီ");
$pwa_otsu->update_progressbar(80);

echo "<script> console.log('decrypt_binary_one_d_array ".json_encode($decrypt_binary_one_d_array)." '); </script>";

echo "<script> console.log('binary one decryption  transpostion start '); </script>";
$binary_re_transposited_one_d_array = $pwa_columnar->re_transposition($decrypt_binary_one_d_array,$user_key);
echo "<script> console.log(' binary_re_transposited_one_d_array binary one dimensional array ".json_encode($binary_re_transposited_one_d_array)." '); </script>";
$debug_array = array();

for($x = 0 ; $x<800; $x++){
	$total = 800;
	$now = $x+1 ;
	$percent = (100 * $now ) / $total ;
	$pwa_otsu->send_to_browser(" အျဖူ အမညး္ ပံုေဖာ္ျခင္း လုပ္ငန္း ".$percent.  " %  ျပီးစီးေနပါျပီ");
	$pwa_otsu->update_progressbar($percent);
	
	for($y =0; $y<600; $y++){

		/* we don't need to read stegano image anymore */
		/* we just need to get re transported array's element */
		
		# read stegano image pixel value and extract r,g,b decimal
		$rgb = imagecolorat($binary_stegano_photo, $x, $y);
		$r = ($rgb >> 16) & 0xFF;
		$debug_r_decimal = $r ;

		# change to binary
		$r_binary = decbin($r);
		$debug_r_binary = $r_binary ;

		# make 8 bit binary string
		$r_binary = make_eight_bit($r_binary);
		$debug_r_8_bit = $r_binary;

		# extract the last  binary string from r
		$r_replace_value = substr($r_binary,7,1) ;
		$debug_r_replace_value = $r_replace_value ;


		# if 1 , change to 255
		# if 0 , will be 0 :D :D :D
		if($r_replace_value == 1) {
			$r_replace_value = 255 ;
		}
		

		$debug_r_zero_255 = $r_replace_value;
		if($x == 400 && $y == 300){
			$debug_array = array($debug_r_decimal,$debug_r_binary,$debug_r_8_bit,$debug_r_replace_value,$debug_r_zero_255);
		}
		


		/* decryption re-transposited one dimensional array */
		/*
		# caclculate current index
		$current_index = ($x+1) * ($y + 1 );
		if($current_index == 480000) $current_index = 0;
		# get 0 or 255 from binary re transposited one dimensional array
		$r_replace_value = $binary_re_transposited_one_d_array[$current_index];
		*/
		// initialize new color
		$gray_decimal = $r_replace_value;

		// initialize new color
		$newColor = $gray_decimal  << 16 | $gray_decimal << 8 | $gray_decimal ;

		// set color at x , y on binary image
		imagesetpixel($binary_photo, $x, $y, $newColor);
	}
}


echo "debug array => ".json_encode($debug_array);
# write blank photo
# this photo will be retrieved binary (balck and white ) photo 
# write cover resource (may be it 'is stegano image ')
imagepng($binary_photo,$binary_retrieve_url);

echo "<br><h2>ျပန္လည္ရရိွေသာ အျဖဴအမည္း ဓာတ္ပံု</h2>";
echo "<img src='".$binary_retrieve_url."'><br>";
		
// gray image encoding
# binary value of the first pixel 

$original_photo_r = 0;
$original_photo_g = 0;
$original_photo_b = 0;

$gray_photo_o = 0 ;

for($x = 0 ; $x<800; $x++){
	$total = 800;
	$now = $x+1 ;
	$percent = (100 * $now ) / $total ;
	$pwa_otsu->send_to_browser("ပံုဝွက္ျခင္း လုပ္ငန္း  ".$percent.  " % ျပီးစီးေနပါျပီ");
	$pwa_otsu->update_progressbar($percent);
	for($y =0; $y<600; $y++){
		# read gray scale secret image pixel
		$gray_value = imagecolorat($gray_photo, $x, $y);

		# convert to decimal
		$gray_decimal = ($gray_value >> 16) & 0xFF;

		# convert to bianry
		$gray_binary = decbin($gray_decimal);

		# make 8 bit binary string
		$gray_binary = make_eight_bit($gray_binary);



		# divided into three part for r,g,b replacemanet
		$r_replace_value = substr($gray_binary,0,3) ;
		$g_replace_value = substr($gray_binary,3,3) ;
		$b_replace_value = substr($gray_binary,6,2) ;

		if($x == 400 && $y == 300){
			$gray_photo_o = array($gray_binary,$r_replace_value,$g_replace_value,$b_replace_value);
		}
		# read cover image pixel value
		$rgb = imagecolorat($cover_photo, $x, $y);

		# extract for red, green , blue value
		$r = ($rgb >> 16) & 0xFF;
		$g = ($rgb >> 8) & 0xFF;
		$b = $rgb & 0xFF;



		# convert to binary nad replace with gray replace value
		$r_binary = decbin($r);
		$r_binary = make_eight_bit($r_binary);
		$r_binary_msb = substr($r_binary,0,5) ;
		$r_binary = $r_binary_msb . $r_replace_value ;
		//substr_replace($r_binary,$r_replace_value,5,3);

		$g_binary = decbin($g);
		$g_binary = make_eight_bit($g_binary);
		$g_binary_msb = substr($g_binary,0,5) ;
		$g_binary = $g_binary_msb . $g_replace_value ;	
		//substr_replace($g_binary,$g_replace_value,5,3);


		$b_binary = decbin($b);
		$b_binary = make_eight_bit($b_binary);
		$b_binary_msb = substr($b_binary,0,6) ;
		$b_binary = $b_binary_msb . $b_replace_value ;	
		//substr_replace($b_binary,$b_replace_value,6,2);

		if($x == 400 && $y == 300){
			$original_photo_r = array($r_binary,$g_binary,$b_binary);
		}
		# convert to decimal
		$r = bindec($r_binary);
		$g = bindec($g_binary);
		$b = bindec($b_binary);

		// initialize new color
		$a = $rgb & 0xFF000000;

		$newColor = $a | $r  << 16 | $g << 8 | $b ;

		// set color at x , y on image (cover resource)
		imagesetpixel($cover_photo, $x, $y, $newColor);
	}
}

//echo "<h2> Hello RGB Binary </h2>";
//echo json_encode($gray_photo_o);
//echo "<br>".json_encode($original_photo_r);
			

//imagepng($gray_secret, 'secre_img_binary.png');

//imagepng($gray_secret,'binary_photo.png');

# write cover resource (may be it 'is stegano image ')
imagepng($cover_photo,$stegano_url);



echo "<br><h2>Grayscale ပံု</h2>";
echo "<img src='".$gray_url."'><br>";

//echo "<br><h2>Binary Image</h2>";
//echo "<img src='binary_photo.png'><br>";


echo "<br><h2>Grayscale ထည့္ဝွက္ထားေသာပံု</h2>";
echo "<img src='".$stegano_url."'><br>";


			
// gray image decoding
# read blank photo
$white_url = "images/white_photo.png";
$white_photo = imagecreatefrompng($white_url);

# read stegano photo
$stegano_photo = imagecreatefrompng($stegano_url);

for($x = 0 ; $x<800; $x++){
	$total = 800;
	$now = $x+1 ;
	$percent = (100 * $now ) / $total ;
	$pwa_otsu->send_to_browser("ပံုေဖာ္ျခင္း လုပ္ငန္း ".$percent.  " %  ျပီးစီးေနပါျပီ");
	$pwa_otsu->update_progressbar($percent);
	
	for($y =0; $y<600; $y++){
		# read stegano image pixel value and extract r,g,b decimal
		$rgb = imagecolorat($stegano_photo, $x, $y);
		$r = ($rgb >> 16) & 0xFF;
		$g = ($rgb >> 8) & 0xFF;
		$b = $rgb & 0xFF;
		# change to binary
		$r_binary = decbin($r);
		$g_binary = decbin($g);
		$b_binary = decbin($b);
		# make 8 bit binary string
		$r_binary = make_eight_bit($r_binary);
		$g_binary = make_eight_bit($g_binary);
		$b_binary = make_eight_bit($b_binary);
		# extract 3-3-2 binary string from r,g,b respectively
		$r_replace_value = substr($r_binary,5,3) ;
		$g_replace_value = substr($g_binary,5,3) ;
		$b_replace_value = substr($b_binary,6,2) ;
		# make new 8 bit binary string
		$new_binary_string = $r_replace_value.$g_replace_value.$b_replace_value ;
		$new_decimal = bindec($new_binary_string);
		// initialize new color
		$a = $rgb & 0xFF000000;
		$newColor = $a | $new_decimal  << 16 | $new_decimal << 8 | $new_decimal ;
		// set color at x , y on image
		imagesetpixel($white_photo, $x, $y, $newColor);
	}
}

# write the retrieved photo
imagepng($white_photo,$retrieve_url);




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


echo "image is stored as ".$_POST['filename']." key binary is ".$key_binary. "key length is ".$key_length." stattus is ".$sts." and len ".strlen($str1)." and ".strlen($str2);
*/
// we have to show 
// 1. original photo
// 2. gray scale photo
// 3. binary photo
// 4. cover photo
// 5. stegano photo

echo "<br><h2>ျပန္လည္ရရိွေသာ Grayscale ဓာတ္ပံု</h2>";
echo "<img src='".$retrieve_url."'><br>";
