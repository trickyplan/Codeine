<?php
/**
 * Codeine Platform
 * @package Drivers
 * @name 
 * @author BreathLess
 * @version 5.0
 * @copyright BreathLess, 2010
 */

function F_Default_Stopword($Args)
  {
        $SW = Data::Read('Static', '{"I":"SWRu"}');
        $Args = array_diff($Args, $SW);

        return $Args;
  }