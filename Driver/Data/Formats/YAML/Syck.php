<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Syck Wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 14.11.10
     * @time 16:08
     */

    $Decode = function ($Call)
    {
        return syck_load($Call['Value']);
    };

    $Encode = function ($Call)
    {
        // TODO Implement Syck Encoding
        return $Call['Value'];
    };

