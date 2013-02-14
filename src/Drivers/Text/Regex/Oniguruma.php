<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Match', function ($Call)
    {
        $Pockets = null;

        mb_ereg($Call['Pattern'], $Call['Value'], $Pockets);

        return $Pockets;
    });

    setFn('All', function ($Call)
    {
        $Results = [];

        mb_ereg_search_init($Call['Value']);

        $Result = mb_ereg_search($Call['Pattern']);

        if($Result)
        {
            $Result = mb_ereg_search_getregs(); //get first result
            do
            {
                foreach ($Result as $IX => $Value)
                    $Results[$IX][] = $Value;

                $Result = mb_ereg_search_regs();//get next result
            }
            while($Result);
        }
        else
            return false;

        return $Results;
    });

