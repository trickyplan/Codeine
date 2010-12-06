<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: YAML Router via Syck
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 10.11.10
     * @time 23:16
     */

  self::Fn('Route', function ($Call)
  {
      return Code::Run(
            array('N'=>'Formats.YAML', 'F'=>'Decode', 'Value' => $Call['Call'])
        );
  });