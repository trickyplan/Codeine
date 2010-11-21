<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: File Output
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 16.11.10
     * @time 3:41
     */

    $Output = function ($Call)
    {
        return file_put_contents($Call['Call'], $Call['Output']);
    };