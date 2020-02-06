<?php
class Pwa_otsu
{
public $gray_image_url;
public function __construct($gray_image_url)
{
	$this->gray_image_url = $gray_image_url;
}


# find histogram of gray scale image
# input => gray scale image url
# will return histogram ( 2 dimentional array)
public function find_histogram($image_url){
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



public function weight($histogram,$threshold_value,$ground){
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

public function mean($histogram,$threshold_value,$ground){
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
	if($lower_sum == 0 ) return 0 ;
	$mean = $upper_sum / $lower_sum;
	return $mean;
}

public function variance($histogram,$threshold_value,$ground,$mean){
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
	if($lower_sum == 0 ) return 0 ;
	$variance = $upper_sum / $lower_sum;
	return $variance;
}

public  function class_variance($fore_variance,$fore_weight,$back_variance,$back_weight){
	# will return the class_variance
	$class_variance =  ($fore_variance * $fore_weight ) + ($back_variance * $back_weight);
	return $class_variance;
}

public function calculate_thresholde($histogram){
$class_variance_array = array();
$this->send_to_browser("finding thresholding value");
for($a = 0; $a < count($histogram) ; $a++){
	$percent = $a + 1 ;
	$this->send_to_browser(" working on ".$percent." / ".count($histogram));
	$this->update_progressbar($percent);
	$threshold_value = $a;
	$foreground = 0;
	$background = 1 ;

	$fore_weight = $this->weight($histogram,$threshold_value,$foreground);
	$back_weight = $this->weight($histogram,$threshold_value,$background);

	$fore_mean = $this->mean($histogram,$threshold_value,$foreground);
	$back_mean = $this->mean($histogram,$threshold_value,$background);

	$fore_variance = $this->variance($histogram,$threshold_value,$foreground,$fore_mean);
	$back_variance = $this->variance($histogram,$threshold_value,$background,$back_mean);

	$class_variance = $this->class_variance($fore_variance,$fore_weight,$back_variance,$back_weight);

	$class_variance_array[count($class_variance_array)] = $class_variance ;
}
$min_index = array_keys($class_variance_array,min($class_variance_array));
return $min_index[0];
}


public function otus_threshold(){
	ob_start();
	echo "<b><span id='pwa_otsu_status'>pwa_otsu_status here </span></b><br>";
	// send to browser
	ob_flush();

	$histo = $this->find_histogram($this->gray_image_url);
	$min_index = $this->calculate_thresholde($histo);

	echo "<script>document.getElementById('pwa_otsu_status').innerHTML =  ' Threshold Value is ".$min_index. "';</script>";
	// send to browser
	ob_flush();
	ob_end_flush(); 
	return $min_index;
}


public function send_to_browser($msg){
	echo "<script>document.getElementById('progress_status').innerHTML =  '".$msg. "';</script>";
	// send to browser
	ob_flush();
}
public function update_progressbar($percent){
	if ($percent == 100){
		echo "<script> $('#progress_bar_div').hide(); </script>";
	}
	else{
		echo "<script> $('#progress_bar_div').show(); </script>";
	}
	echo "<script> $('#progress_bar').css('width', '".$percent."%'); </script>";
	// send to browser
	ob_flush();
}


}

?>