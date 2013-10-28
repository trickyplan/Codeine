<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn ('Detect', function ($Call)
    {
        foreach ($Call['Locales']['Hosts'] as $Host => $Locale)
            if (preg_match('/'.$Host.'/', $Call['RHost']))
            {
                $Call['Locale'] = $Locale;
                break;
            }

        F::Log('Hosts suggest locale *'.$Call['Locale'].'* by regex *'.$Host.'*', LOG_INFO);

        return $Call;
    });