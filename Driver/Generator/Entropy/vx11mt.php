<?php

  function F_vx11mt_Get($Args)
  {
      $PlaneSize = 10;
      for ($ax=1;$ax<=$PlaneSize;$ax++) for ($ay=1;$ay<=$PlaneSize;$ay++) $Plane[$ax][$ay]=mt_rand($Args['Min'],$Args['Max']);
      $Power=mt_rand(2,$PlaneSize);
      $CursorY=1;$CursorX=1;
      for ($bg=1;$bg<$Power;$bg++)
      {
	  $Wind=mt_rand(0,$PlaneSize/2)-$PlaneSize/2;
	  $CursorY++;
	  $CursorX+=$Wind;
	      if ($CursorX>$PlaneSize) $CursorX=$PlaneSize-($CursorX-$PlaneSize);
	      if ($CursorX<1) $CursorX=1+(1-$CursorX);
      }
      return $Plane[$CursorX][$CursorY];
  }