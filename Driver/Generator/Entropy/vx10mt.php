<?php
  function F_vx10mt_Get ($Args)
  {
      mt_srand();
      do {
	  $GammaCount = mt_rand(1, 6);
	  $GammaLevel = mt_rand($Args['Min'], $Args['Max']);
	  $CubeSize = 5;
	  for ($x = 1; $x <= $CubeSize; $x ++)
	      for ($y = 1; $y <= $CubeSize; $y ++)
		  for ($z = 1; $z <= $CubeSize; $z ++) {
		      $WORK[$x][$y][$z] = mt_rand($Args['Min'], $Args['Max']);
		  }
	  for ($g = 1; $g <= $GammaCount; $g ++) {
	      $GammaS[$g] = mt_rand(0, $GammaLevel);
	      for ($mx = 1; $mx <= round($CubeSize); $mx ++)
		  for ($my = 1; $my <= round($CubeSize); $my ++)
		      for ($mz = 1; $mz <= round($CubeSize); $mz ++) {
			  $WORK[$mx][$my][$mz] += mt_rand(0, $GammaS[$g]);
			  if ($WORK[$mx][$my][$mz] > $Args['Max'])
			      $WORK[$mx][$my][$mz] = $WORK[$mx][$my][$mz] % $Args['Max'];
			  if ($WORK[$mx][$my][$mz] < $Args['Min'])
			      $WORK[$mx][$my][$mz] = $WORK[$mx][$my][$mz] % $Args['Min'];
		      }
	  }
	  $tx = mt_rand(1, $CubeSize);
	  $ty = mt_rand(1, $CubeSize);
	  $tz = mt_rand(1, $CubeSize);
      } while (! ($WORK[$tx][$ty][$tz] >= $Args['Min']) and ($WORK[$tx][$ty][$tz] <= $Args['Max']));
      return $WORK[$tx][$ty][$tz];
  }