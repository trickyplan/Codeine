<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        if (isset($Call['View']['Debug']) && $Call['View']['Debug'] && !isset($Call['No View Debug']))
            $Call['Value'] =
                '<!-- '.$Call['Scope'].':'.$Call['ID'].' started -->'
                ."\n".$Call['Value']."\n"
                .'<!-- '.$Call['Scope'].':'.$Call['ID'].' ended -->';

        return $Call;
    });