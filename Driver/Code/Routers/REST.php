<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: REST Router
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 10.11.10
     * @time 23:16
     */

    self::Fn('Route', function ($Call)
    {
        $Routed = explode('/',$Call['Call']);

        list($Type,$Name) = $Routed;

        switch ($_SERVER['REQUEST_METHOD'])
        {
            case 'get': $REST = 'Show'; break;
            case 'put': $REST = 'Create'; break;
            case 'post': $REST = 'Update'; break;
            case 'delete': $REST = 'Delete'; break;
        }
        
        $Routed['F'] = $Type.'/'.$REST;
        
        
        return null;
    });