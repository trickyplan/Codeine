<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Russian Passport
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 24.11.10
     * @time 5:16
     */

    self::Fn('Expire', function ($Call)
    {
        $Time = time();
        $Pass = $Call['Passport'];
        $Born = $Call['Born'];
        $Age  = floor ((time() - $Born)/31622400);

        $Y20 = $Born + 20*31449600;
        $Y45 = $Born + 45*31449600;

        return !(($Pass < $Y20 && $Age >= 20) or ($Pass < $Y45 && $Age>=45));
    });