<?php
/**
 * Codeine Platform
 * @package Drivers
 * @name 
 * @author BreathLess
 * @version 5.0
 * @copyright BreathLess, 2010
 */

function F_Default_Stemmer($Args)
  {
      include Engine . 'Classes/Stem_Ru.php';
        $stemmer = new Stem_Ru();
        $Output = array();
        foreach ($Args as $Arg)
            $Output[] = $stemmer->stem_word($Arg);
        return $Output;
  }