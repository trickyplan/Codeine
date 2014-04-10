<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Test', function ($Call)
    {
        $String = '';

        $Start = microtime(true);

        for ($a = 1; $a<$Call['Cycles']; $a++)
            $String.= chr(rand(65, 90));

        $Stop = microtime(true);
        return $Call['Cycles']/($Stop-$Start);
    });