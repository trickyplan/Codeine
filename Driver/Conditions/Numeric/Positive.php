<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Positive checker
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 30.10.10
     * @time 5:06
     */

     self::Fn('Check', function ($Call)
     {
         return ($Call['Value']>=0);
     });
