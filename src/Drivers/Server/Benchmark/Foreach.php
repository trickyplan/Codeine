<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Test', function ($Call)
    {
        $Array = [];

        for ($a = 1; $a<24; $a++)
            $Array[$a] = $a*$a;

        $Start = microtime(true);
            for ($a = 1; $a <$Call['Cycles']; $a++)
                foreach ($Array as $Key => $Value)
                    {
                        $Value; $Key;
                    }

        $Stop = microtime(true);
        return $Call['Cycles']/($Stop-$Start);
    });