<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Dumper
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 16.11.10
     * @time 3:54
     */

    $Render = function ($Call)
    {
        return var_export($Call['Body'], true);
    };