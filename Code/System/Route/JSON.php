<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: JSON Router
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 10.11.10
     * @time 23:16
     */

  $Route = function ($Call)
  {      
      if ($Routed = json_decode($Call['Call'], true))
        return $Routed;
      else
        return null;
  };