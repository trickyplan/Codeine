<?php
/**
 * Codeine Platform
 * @package Drivers
 * @name 
 * @author BreathLess
 * @version 0.1
 * @copyright BreathLess, 2010
 */

function F_Sun_Sunrise($Args)
  {
    return strftime('%X',date_sunrise(time(), SUNFUNCS_RET_TIMESTAMP, $Args['Latitude'], $Args['Longitude']));
  }

function F_Sun_Sunset($Args)
  {
    return strftime('%X',date_sunset(time(), SUNFUNCS_RET_TIMESTAMP, $Args['Latitude'], $Args['Longitude']));
  }

function F_Sun_Suntime($Args)
  {
    $Sunrise = date_sunrise(time(), SUNFUNCS_RET_TIMESTAMP, $Args['Latitude'], $Args['Longitude']);
    $Sunset = date_sunset(time(), SUNFUNCS_RET_TIMESTAMP, $Args['Latitude'], $Args['Longitude']);
    $Time = ($Sunset-$Sunrise)/60;
    return round($Time/60).':'.($Time%60);
  }

function F_Sun_Sun($Args)
  {
    $Sunrise = date_sunrise(time(), SUNFUNCS_RET_TIMESTAMP, $Args['Latitude'], $Args['Longitude']);
    $Sunset = date_sunset(time(), SUNFUNCS_RET_TIMESTAMP, $Args['Latitude'], $Args['Longitude']);
    $Output = 'Moon';
    $Time = time();
    if ($Time>$Sunrise and $Time<$Sunset)
        $Output = 'Sun';
    return $Output;
  }