<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Required Contract Checker
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 30.10.10
     * @time 5:31
     */

    $Check = function ($Args)
    {        
        if (isset($Args['Contract']['Required']) && $Args['Contract']['Required'] && empty($Args['Data']))
            return false;
        else
            return true;
    };