<?php
ini_set('memory_limit','-1');
/* 
ob_start();

echo('doing something...');

// send to browser
ob_flush();

// ... do long running stuff
echo('still going...');

ob_flush();

echo('done.');
ob_end_flush(); 
*/

ob_start();

echo "<h2>Otsu's thresholding method</h2>";
echo "<b><span id='status'>status here </span></b><br>";

// send to browser
ob_flush();
$msg = "from php";
echo "<script>document.getElementById('status').innerHTML =  '".$msg. "';</script>";
// send to browser
ob_flush();


function send_to_browser($msg){
	echo "<script>document.getElementById('status').innerHTML =  '".$msg. "';</script>";
	// send to browser
	ob_flush();
}
// first we read our image
$original_photo_url = "images/original_photo.png";
$grayscale_photo_url = "images/grayscale_photo.png";

$original_photo = imagecreatefrompng($original_photo_url);
// covert to gray scale 
imagefilter($original_photo, IMG_FILTER_GRAYSCALE);
// write gray telegraph photo 
imagepng($original_photo,$grayscale_photo_url);

# find histogram of gray scale image
# input => gray scale image url
# will return histogram ( 2 dimentional array)
function find_histogram($image_url){

	$photo = imagecreatefrompng($image_url);
	# create array (may be Histogram)
	$histogram = array();

	# we have to initialize the histogram to all ZERO
	for($i = 0 ; $i< 256; $i++){
		$histogram[$i] = 0 ;
	}

	# get the size of gray scale image 
	# we need to know how many time we should loop
	list($width, $height, $type, $attr) = getimagesize($image_url);
	for($i = 0 ; $i<$width ; $i++){
		for($j = 0; $j<$height; $j++){
			# find the pixel value
			# may be between 0 to 255 
			$gray_value = imagecolorat($photo, $i, $j);
			$gray_decimal = ($gray_value >> 16) & 0xFF;
			$histogram[$gray_decimal]++;
		}
	}

	return $histogram;
}

$histogram = find_histogram($grayscale_photo_url);

# yeah we get histogram of gray scale photo
echo json_encode($histogram);

echo "HISTOGRAM count is ".count($histogram);

$threshold_value = 125;
$foreground = 0;
$background = 1 ;
echo "<h2> foreground weight for 125 </h2>";
$fore_weight = weight($histogram,$threshold_value,$foreground);
$back_weight = weight($histogram,$threshold_value,$background);
echo $fore_weight . " : " . $back_weight ;

echo "<h2> foreground mean for 125 </h2>";
$fore_mean = mean($histogram,$threshold_value,$foreground);
$back_mean = mean($histogram,$threshold_value,$background);
echo $fore_mean . " : " . $back_mean ;

echo "<h2> foreground variance for 125 </h2>";
$fore_variance = variance($histogram,$threshold_value,$foreground,$fore_mean);
$back_variance = variance($histogram,$threshold_value,$background,$back_mean);
echo $fore_variance . " : " . $back_variance ;

echo "<h2> Class Variance </h2>";
$class_variance = class_variance($fore_variance,$fore_weight,$back_variance,$back_weight);
echo $class_variance;

$class_variance_array = array();
send_to_browser("finding thresholding value");
for($a = 0; $a < count($histogram) ; $a++){
	$percent = $a + 1 ;
	send_to_browser(" working on ".$percent." / ".count($histogram));
	$threshold_value = $a;
	$foreground = 0;
	$background = 1 ;

	$fore_weight = weight($histogram,$threshold_value,$foreground);
	$back_weight = weight($histogram,$threshold_value,$background);

	$fore_mean = mean($histogram,$threshold_value,$foreground);
	$back_mean = mean($histogram,$threshold_value,$background);

	$fore_variance = variance($histogram,$threshold_value,$foreground,$fore_mean);
	$back_variance = variance($histogram,$threshold_value,$background,$back_mean);

	$class_variance = class_variance($fore_variance,$fore_weight,$back_variance,$back_weight);

	$class_variance_array[count($class_variance_array)] = $class_variance ;
}

echo "<h2> Class Variance Array </h2>";
echo json_encode($class_variance_array);

echo "<h2> Minimun Value and Index </h2>";
$min_index = array_keys($class_variance_array,min($class_variance_array));
echo min($class_variance_array) . " : ". $min_index[0];


function weight($histogram,$threshold_value,$ground){
	# will return the weight
	$sum = 0 ;
	if($ground == 0 ){
		# may be foreground
		for($i = 0 ; $i< $threshold_value; $i++){
			$sum += $histogram[$i];
		}
	}
	else{
		# may be background
		for($i = $threshold_value ; $i<count($histogram) ; $i++){
			$sum += $histogram[$i] ;
		}
	}

	$divider = count($histogram) * count($histogram);
	$weight =  $sum / $divider;
	return $weight;
}

function mean($histogram,$threshold_value,$ground){
	# will return the mean
	$upper_sum = 0;
	$lower_sum = 0;
	if($ground == 0 ){
		# may be foreground
		for($i = 0 ; $i< $threshold_value; $i++){
			$upper_sum += $i * $histogram[$i] ;
			$lower_sum += $histogram[$i];
		}
	}
	else{
		# may be background
		for($i = $threshold_value ; $i<count($histogram) ; $i++){
			$upper_sum += $i * $histogram[$i] ;
			$lower_sum += $histogram[$i];
		}
	}

	$mean = $upper_sum / $lower_sum;
	return $mean;
}

function variance($histogram,$threshold_value,$ground,$mean){
	# will return the variance
	$upper_sum = 0;
	$lower_sum = 0;
	if($ground == 0 ){
		# may be foreground
		for($i = 0 ; $i< $threshold_value; $i++){
			$upper_sum += ($i - $mean) * ($i - $mean) * $histogram[$i];
			$lower_sum += $histogram[$i];
		}
	}
	else{
		# may be background
		for($i = $threshold_value ; $i<count($histogram) ; $i++){
			$upper_sum += ($i - $mean) * ($i - $mean) * $histogram[$i];
			$lower_sum += $histogram[$i];
		}
	}

	$variance = $upper_sum / $lower_sum;
	return $variance;
}

function class_variance($fore_variance,$fore_weight,$back_variance,$back_weight){
	# will return the class_variance
	$class_variance =  ($fore_variance * $fore_weight ) + ($back_variance * $back_weight);
	return $class_variance;
}


#################################################################
?>