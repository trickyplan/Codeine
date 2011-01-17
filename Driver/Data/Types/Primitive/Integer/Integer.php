<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Integer number
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 01.12.10
     * @time 20:11
     */

    self::Fn('Input', function ($Call)
    {
        return $Call['Input'];
    });

    self::Fn('Output', function ($Call)
    {
        return $Call['Input'];
    });
    
    self::Fn('Validate', function ($Call)
    {
        return is_numeric($Call['Input']);
    });

    self::Fn('String', function ($Call)
    {
        return (string) $Call['Input'];
    });

    self::Fn('Compare', function ($Call)
    {
        return ($Call['A'] > $Call['B']);
    });