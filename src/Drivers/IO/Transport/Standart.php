<?php

    /* Codeine
     * @author BreathLess
     * @description: StdOut Transport
     * @package Codeine
     * @version 8.x
     * @date 29.07.21
     * @time 21:45
     */

    setFn('Send', function ($Call)
    {
        $f = fopen('php://stdout', 'a+');
        fwrite($f, $Call['Message']);
        fclose($f);
    });

    setFn('Receive', function ($Call)
    {
        $f = fopen('php://stdin', 'r');
        $Call = fgets($f);
        fclose($f);
        return $Call;
    });
