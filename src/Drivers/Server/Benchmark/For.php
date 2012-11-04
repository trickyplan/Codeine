<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Test', function ($Call)
    {
        $Cycles = 4000000;

        $Start = microtime(true);
            for ($a = 1; $a<$Cycles; $a++);

        $Stop = microtime(true);
        return round(1000/($Stop-$Start));
    });