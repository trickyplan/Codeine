<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: 
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 11.03.11
     * @time 1:45
     */

    self::Fn('Test', function ($Call)
    {
        $ST = microtime(true);
        $file = file_get_contents ($Call['URL']);
        $ST = microtime(true) - $ST;
        // FIXME Wget
        return round(round(strlen($file)/1024)/$ST,2);
    });
