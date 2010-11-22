<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Value checker
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 30.10.10
     * @time 5:31
     */


    self::Fn('Check', function ($Call)
    {
        if (isset($Call['Contract']['Value']))
        {
            if (isset($Call['Contract']['RelativeDeviation']))
            {
                $Deviation = $Call['Contract']['RelativeDeviation']/$Call['Contract']['Value'];
                
                return (($Call['Data']['Value'] > ($Call['Contract']['Value']-$Deviation))
                            && ($Call['Data']['Value'] < ($Call['Contract']['Value']+$Deviation)));
            }

            if (isset($Call['Contract']['AbsoluteDeviation']))
                return (
                   ($Call['Data']['Value'] > $Call['Contract']['Value']-$Call['Contract']['AbsoluteDeviation'])
                            && ($Call['Data']['Value'] <
                                ($Call['Contract']['Value']+$Call['Contract']['AbsoluteDeviation'])));

            return ($Call['Data']['Value'] == $Call['Contract']['Value']);
        }
        else
            return true;
    });