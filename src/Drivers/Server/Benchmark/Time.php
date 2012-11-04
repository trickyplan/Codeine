<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Test', function ($Call)
    {
        $Cycles = 1000000-2;

        $Start = microtime(true);
            for ($a = 1; $a<$Cycles; $a++)
                microtime(true);

        $Stop = microtime(true);
        return round(1000/($Stop-$Start));
    });