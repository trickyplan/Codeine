<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        if (isset($_SERVER['HTTP_REFERER']))
        {
            $Call['HTTP']['Referer'] = $_SERVER['HTTP_REFERER'];
            F::Log('Referrer is: '.$Call['HTTP']['Referer'], LOG_INFO);
        }
        else
            F::Log('Referrer empty', LOG_INFO);

        return $Call;
    });