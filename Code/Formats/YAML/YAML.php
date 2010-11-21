<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: YAML PECL Wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 14.11.10
     * @time 16:08
     */

    $Decode = function ($Call)
    {
        return yaml_parse($Call['Value']);
    };

    $Encode = function ($Call)
    {
        return yaml_emit($Call['Value']);
    };

