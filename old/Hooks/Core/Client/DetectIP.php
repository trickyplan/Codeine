<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: IP Detector
     * @package Codeine
     * @subpackage Hooks
     * @version 5.0
     * @date 28.10.10
     * @time 3:24
     */

    $Hook = function ()
    {
        define ('_IP', $_SERVER['REMOTE_ADDR']);
    };