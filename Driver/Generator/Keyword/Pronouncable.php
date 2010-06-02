<?php

function F_Pronouncable_Generate($Args)
{
	$length = $Args["Length"];
	
	$vowels = array("a", "e", "u");
	$cons = array("b", "c", "d", "g", "h", "j", "k","m", "n", "p", "r", "s", "t", "u", "v", "w", "tr",
	"cr", "br", "fr", "th", "dr", "ch", "ph", "wr", "st", "sp", "sw", "pr", "sl", "cl");
	
	$num_vowels = sizeof($vowels);
	$num_cons = sizeof($cons);
	
	for($i = 0; $i < $length; $i++){
		$password .= $cons[rand(0, $num_cons - 1)] . $vowels[rand(0, $num_vowels - 1)];
	}
	return strtoupper(substr($password, 0, $length));
}


?>
	
