<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        if (isset($Call['View']['Debug']) && $Call['View']['Debug'] && !isset($Call['No View Debug']) && !empty($Call['Value']))
        {
            $ID = $Call['Scope'].':'.$Call['ID'].(isset($Call['Context'])? ':'.$Call['Context']: '');

            $Call['Value'] =
                "\n".'<!-- '.$ID.' started -->'
                ."\n".$Call['Value']."\n"
                .'<!-- '.$ID.' ended -->';
        }

        return $Call;
    });