<?php

// first we read our image
$original_photo_url = "images/original_photo.png";
$grayscale_photo_url = "images/grayscale_photo.png";

$original_photo = imagecreatefrompng($original_photo_url);
// covert to gray scale 
imagefilter($original_photo, IMG_FILTER_GRAYSCALE);
// write gray telegraph photo 
imagepng($original_photo,$grayscale_photo_url);

require('pwa_otsu.php');
$pwa_otsu = new Pwa_otsu($grayscale_photo_url);
$value = $pwa_otsu->otus_threshold();
echo "Value is ".$value;

?>