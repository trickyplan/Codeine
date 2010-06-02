<?php

  function F_Range_Lookup($Data)
  {
     $Output = array();
     
     for ($A = $Data->From; $A<=$Data->To; $A+=$Data->Inc)
        $Output[] = $A;
     
     return $Output;
  }