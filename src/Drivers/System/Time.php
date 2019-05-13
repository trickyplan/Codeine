<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */


    setFn('Get', function ($Call)
    {
        if (isset($Call['Modes'][$Call['Mode']]))
            ;
        else
            $Call['Mode'] = 'Normal';

        return F::Live($Call['Modes'][$Call['Mode']], $Call)+F::Dot($Call, 'Time.Offset');
    });
    
    setFn('isLeapYear?', function ($Call)
    {
        // date('L') is broken

        if($Call['Year'] % 4 == 0 && $Call['Year'] % 100 != 0)
            $Leap = true;
        elseif($Call['Year'] % 400 == 0)
            $Leap = true;
        else
            $Leap = false;

        return $Leap;
    });