<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: SimpleXML Driver
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 14.11.10
     * @time 16:07
     */

    $Decode = function ($Call)
    {
        return simplexml_load_string($Call['Value']);
    };

    $Encode = function ($Call)
    {
        // TODO Implement encode method
        return $Call['Value'];
    };

