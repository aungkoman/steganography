<?php
class Pwa_columnar
{
public $transported_array;
public function __construct()
{
	//$this->gray_image_url = $gray_image_url;
} // end for constructor 


# sort string 
public function sort_string($str){
	$stringParts = str_split($str);
	sort($stringParts);
	$sorted_str = implode('', $stringParts);
	return $sorted_str;
} // end for sort string 


public function index_changes($str,$new_string){
	# sort the string
	$changes = array();
	for($i=0; $i<strlen($str); $i++){
		// we need to find from the first char
		$char = $str[$i];
		$char .= "";

		// find the new index from sorted string
		$position = strpos($new_string, $char);

		// replace with * char in sorted string
		$new_string = substr_replace($new_string,"*",$position,1);

		// add new changes value to changes array
		$changes[$i] = $position;
	}
	return $changes;
} // end for index changes function (method)


public function transposition($array,$key){
	# one dimensional array 
	$array_count = count($array);

	# create new array
	$key_array = array
 		(
  			array()
  		);

 	$row_count = 0;
 	$j = 0; 		
 	while($j < $array_count){
		for($i = 0 ; $i<strlen($key); $i++){
			if($j >= $array_count ){
				// medium for gray color :P :P :P 
				// i have no idea about that color
				// hay , we update the value 125 to 0 
				// for just black 
				$key_array[$row_count][$i] = 0;
			}
			else{
				$key_array[$row_count][$i] = $array[$j];				
			}
			$j++;
			# no , we don't stop the row 
			/*
			if($j == $array_count){
				// just stop inner  for loop
				$i = strlen($key);
			}
			*/
		}
		$row_count++;
		// while loop automatically while $j => array_count
	}

	// so we get key array ( 2 d table)
	// sort the key string
	$sorted_str = $this->sort_string($key);
	$changes = $this->index_changes($key,$sorted_str);
	# create new array
	$transform_array = array
 		(
  			array()
  		);

	$array_index = 0 ;
 	$row_count = 0;
 	$j = 0;

 	while($j < $array_count){
		for($i = 0 ; $i<strlen($key); $i++){
			# we have to choose which column to insert current colum 
			# current column is $i 
			$new_column = $changes[$i];
			$transform_array[$row_count][$i] = $key_array[$row_count][$new_column];
			$j++;
			if($j == $array_count){
				// just stop inner  for loop
				$i = strlen($key);
			}
		}
		$row_count++;
		// while loop automatically while $j => array_count
	}

	# so we get transform array ( the sorted table )
	# we need to convert to one dimensional array
	$transported_array = array();
	for($j = 0 ; $j < $row_count ; $j++){
		# it's just column count 
		for($i = 0; $i<count($transform_array[$j]) ; $i++){
			$transported_array[count($transported_array)] = $transform_array[$j][$i];
		}
	}
	# finally we get transported one dimensionaal array 
	return $transported_array;
} // end for transposition method 

// we need 
# one dimensional array
# key ( to know table column count )
public function re_transposition($array,$key){
	# one dimensional array 
	$array_count = count($array);

	# create new array
	$key_array = array
 		(
  			array()
  		);

 	$row_count = 0;
 	$j = 0; 		



 	while($j < $array_count){


		for($i = 0 ; $i<strlen($key); $i++){
			if($j >= $array_count ){
				// medium for gray color :P :P :P 
				// i have no idea about that color
				$key_array[$row_count][$i] = 125;
			}
			else{
				$key_array[$row_count][$i] = $array[$j];				
			}
			$j++;
			# no , we don't stop the row 
			/*
			if($j == $array_count){
				// just stop inner  for loop
				$i = strlen($key);
			}
			*/
		}
		$row_count++;
		// while loop automatically while $j => array_count
	}

	// so we get key array ( 2 d table)
	// sort the key string
	$sorted_str = $this->sort_string($key);
	$changes = $this->index_changes($sorted_str,$key);

	# create new array
	$transform_array = array
 		(
  			array()
  		);

	$array_index = 0 ;
 	$row_count = 0;
 	$j = 0;

 	while($j < $array_count){
		for($i = 0 ; $i<strlen($key); $i++){
			# we have to choose which column to insert current colum 
			# current column is $i 
			$new_column = $changes[$i];
			$transform_array[$row_count][$i] = $key_array[$row_count][$new_column];
			$j++;
			if($j == $array_count){
				// just stop inner  for loop
				$i = strlen($key);
			}
		}
		$row_count++;
		// while loop automatically while $j => array_count
	}

	# so we get transform array ( the sorted table )
	# we need to convert to one dimensional array
	$transported_array = array();
	for($j = 0 ; $j < $row_count ; $j++){
		# it's just column count 
		for($i = 0; $i<count($transform_array[$j]) ; $i++){
			$transported_array[count($transported_array)] = $transform_array[$j][$i];
		}
	}

	# finally we get transported one dimensionaal array 
	return $transported_array;
} // end for re transposition method 


} // end for Pwa_columnar class 

# class usage section 

// $user_key = "userkey123654";
// $pwa_columnar = new Pwa_columnar();
// echo "user key is ".$user_key. " <br> sorted key is ".$pwa_columnar->sort_string($user_key);

// $sorted_str = $pwa_columnar->sort_string($user_key);
// echo "changes are => ".json_encode($pwa_columnar->index_changes($user_key,$sorted_str));

# new array
// $new_array = array();
// for($i = 0 ; $i<100; $i++){
//	$new_array[$i] = $i*12 ;
// }

/*
echo "<br> transposition  New Array ".json_encode($new_array) ;
echo "<br> using the key ".$user_key;
echo "<br> ".json_encode($pwa_columnar->transposition($new_array,$user_key)) ;
echo "<br>".count($new_array)." and ".count($pwa_columnar->transposition($new_array,$user_key)) ;
*/


# decoding transpoted stegano photo
# so, we need  one dimensitional array of transported element
# and we create 2 d table using sorted user key
# then we consturct the normal table by using normal user key 
# finally we create one dimensition array to decrypt 

?>