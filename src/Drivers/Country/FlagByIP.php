<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Get', function ($Call)
    {
        $Country = strtolower(F::Run('System.GeoIP', 'Country', $Call));
        $Flag = F::findFile('Assets/Country/img/flags/'.$Country.'.png');

        if ($Flag)
            ;
        else
            $Flag = F::findFile('/Assets/Country/img/flags/un.png');

        $Call['Output']['Content'] = $Flag;

        return $Call;
    });