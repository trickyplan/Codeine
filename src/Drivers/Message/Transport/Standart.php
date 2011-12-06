<?php

    /* Codeine
     * @author BreathLess
     * @description: StdOut Transport
     * @package Codeine
     * @version 6.0
     * @date 29.07.11
     * @time 21:45
     */

    self::setFn('Send', function ($Call)
    {
        $f = fopen('php://stdout', 'a+');
        fwrite($f, $Call['Message']);
        fclose($f);
    });

    self::setFn('Receive', function ($Call)
    {
        $f = fopen('php://stdin', 'r');
        $Call = fgets($f);
        fclose($f);
        return $Call;
    });
