<?php
$user_key = "aungkoman63954634415516";

echo "Original User Key => ".$user_key."<br>";

$stringParts = str_split($user_key);
sort($stringParts);
$sorted_string = implode('', $stringParts);

echo "<br>Sorted user key => ".$sorted_string. "<br>"; 
echo "<br> No. 9 in sorted ".strpos($sorted_string, "9");

echo "<br>len for sorted string is ".strlen($sorted_string);
echo "<br>len for user_key string is ".strlen($user_key);
// we need to know changes index
// old => new index

$changes = array();
for($i=0; $i<strlen($user_key); $i++){
	// we need to find from the first char
	$char = $user_key[$i];
	$char .= "";

	// find the new index from sorted string
	//echo strpos($sorted_string, $char)."<br>";
	$position = strpos($sorted_string, $char);
	$sorted_string = substr_replace($sorted_string,"*",$position,1);
	//$changes[count($changes)]= array("old_index"=>$i,"new_index"=>$position);
	$changes[count($changes)] = $position;
	// ohh we can't believe strpos
	// it's bull shit
	// fuck off function
	// we have to do our own search
	// wtf***

	/* so funny guy */
	/*
	$own_search = null;
	for($j=0; $j < strlen($sorted_string); $j++){
		if($char == $sorted_string[$j]) {
			// save position
			$own_search = $j;
			// stop loop
			$j = strlen($sorted_string);
		}
	}
	*/



	// so we have two index
	// i is old and $position is new 

	//echo "<br>The char $char in $user_key  Old index $i => New Index $position OR $own_search in $sorted_string";
	//str_replace($char, $char, "Hello world!");

	//echo "Sub string : ".substr_replace($user_key,"*",$position,1);

}

echo "<br>user_key => ".$user_key;
echo "<br>sorted_string => ".$sorted_string;
echo "<br>".json_encode($changes);

?>
