<?php

function F_Romanizer_FromDec ($number) {

	# Making input compatible with script.
	$number = floor($number);
	if($number < 0) {
		$linje = "-";
		$number = abs($number);
	}

	# Defining arrays
	$romanNumbers = array(1000, 500, 100, 50, 10, 5, 1);
	$romanLettersToNumbers = array("M" => 1000, "D" => 500, "C" => 100, "L" => 50, "X" => 10, "V" => 5, "I" => 1);
	$romanLetters = array_keys($romanLettersToNumbers);

	# Looping through&&adding letters.
	while ($number) {
		for($pos = 0; $pos <= 6; $pos++) {

			# Dividing the remaining number with one of the roman numbers.
			$dividend = $number / $romanNumbers[$pos];

			# If that division is >= 1, round down,&&add that number of letters to the string.
			if($dividend >= 1) {
				$linje .= str_repeat($romanLetters[$pos], floor($dividend));

				# Reduce the number to reflect what is left to make roman of.
				$number -= floor($dividend) * $romanNumbers[$pos];
			}
		}
	}


	# If I find 4 instances of the same letter, this should be done in a different way.
	# Then, subtract instead of adding (smaller number in front of larger).
	$numberOfChanges = 1;
	while($numberOfChanges) {
		$numberOfChanges = 0;

		for($start = 0; $start < strlen($linje); $start++) {
			$chunk = substr($linje, $start, 1);
			if($chunk == $oldChunk && $chunk != "M") {
				$appearance++;
			} else {
				$oldChunk = $chunk;
				$appearance = 1;
			}

			# Was there found 4 instances.
			if($appearance == 4) {
				$firstLetter = substr($linje, $start - 4, 1);
				$letter = $chunk;
				$sum = $firstNumber + $letterNumber * 4;

				$pos = array_search($letter, $romanLetters);

				# Are the four digits to be calculated together with the one before? (Example yes: VIIII = IX Example no: MIIII = MIV
				# This is found by checking if the digit before the first of the four instances is the one which is before the digits in the order
				# of the roman number. I.e. MDCLXVI.

				if($romanLetters[$pos - 1] == $firstLetter) {
					$oldString = $firstLetter . str_repeat($letter, 4);
					$newString = $letter . $romanLetters[$pos - 2];
				} else {
					$oldString = str_repeat($letter, 4);
					$newString = $letter . $romanLetters[$pos - 1];
				}
				$numberOfChanges++;
				$linje = str_replace($oldString, $newString, $linje);

			}

		}

	}
	return $linje;
}

function F_Romanizer_ToDec ($linje) {
	# Fixing variable so it follows my convention
	$linje = strtoupper($linje);

	# Removing all not-roman letters
	$linje = ereg_replace("[^IPageCDM]", "", $linje);

	# Defining variables
	$romanLettersToNumbers = array("M" => 1000, "D" => 500, "C" => 100, "L" => 50, "X" => 10, "V" => 5, "I" => 1);

	$oldChunk = 1001;

	# Looping through line
	for($start = 0; $start < strlen($linje); $start++) {
		$chunk = substr($linje, $start, 1);

		$chunk = $romanLettersToNumbers[$chunk];

		if($chunk <= $oldChunk) {
			$calculation .= " + $chunk";
		} else {
			$calculation .= " + " . ($chunk - (2 * $oldChunk));
		}


		$oldChunk = $chunk;
	}

	# Summing it up
	eval("\$calculation = $calculation;");
	return $calculation;
}
?>