<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        if ((null !== $Call['Value'])
            && isset($Call['View']['Layouts']['Debug'])
            && $Call['View']['Layouts']['Debug'])

            $Call['Value'] = "\n"
                .'<!-- '.$Call['Scope'].':'.$Call['ID'].' started -->'
                ."\n".$Call['Value']."\n"
                .'<!-- '.$Call['Scope'].':'.$Call['ID'].' ended -->';

        return $Call;
    });