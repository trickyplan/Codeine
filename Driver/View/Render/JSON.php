<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: JSON Renderer
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 16.11.10
     * @time 3:38
     */

    $Render = function ($Call)
    {
        return json_encode($Call['Body']);
    };