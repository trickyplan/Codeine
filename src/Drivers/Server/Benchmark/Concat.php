<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Test', function ($Call)
    {
        $Cycles = 500000;

        $String = '';

        $Start = microtime(true);

        for ($a = 1; $a<$Cycles; $a++)
            $String.= chr(rand(65, 90));

        $Stop = microtime(true);
        return round(1000/($Stop-$Start));
    });