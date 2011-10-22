<?php

    /* Codeine
     * @author BreathLess
     * @description: Positive checker
     * @package Codeine
     * @version 6.0
     * @date 30.10.10
     * @time 5:06
     */

     self::Fn('Check', function ($Call)
     {
         return ($Call['Value']>=0);
     });
