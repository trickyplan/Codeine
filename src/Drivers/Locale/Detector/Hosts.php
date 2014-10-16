<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn ('Detect', function ($Call)
    {
        if (isset($Call['HTTP']['Host']))
            foreach ($Call['Locales']['Hosts'] as $Host => $Locale)
                if (preg_match('/'.$Host.'/', $Call['HTTP']['Host']))
                {
                    $Call['Locale'] = $Locale;
                    F::Log('Hosts suggest locale *'
                        .$Call['Locale']
                        .'* by regex *'.$Host.'*', LOG_INFO + 0.5);
                    break;
                }


        return $Call;
    });