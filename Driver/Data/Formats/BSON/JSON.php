<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: BSON Default Driver
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 14.11.10
     * @time 16:05
     */

    $Decode = function ($Call)
    {
        return bson_decode($Call['Value']);
    };

    $Encode = function ($Call)
    {
        return bson_encode($Call['Value']);
    };

