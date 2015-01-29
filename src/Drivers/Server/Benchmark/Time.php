<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Test', function ($Call)
    {
        $Start = microtime(true);

            for ($a = 1; $a<$Call['Cycles']; $a++)
                microtime(true);

        $Stop = microtime(true);
        return $Call['Cycles']/($Stop-$Start);
    });