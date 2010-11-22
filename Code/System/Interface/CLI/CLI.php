<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: CLI Interface
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 17.11.10
     * @time 16:38
     */

    self::Fn('Detect', function ($Call)
    {
        return isset($_SERVER['argv']);
    });

    self::Fn('Input', function ($Call)
    {
        unset($_SERVER['argv'][0]);
        $Call = array();

        foreach ($_SERVER['argv'] as $Var)
        {
            list($Key, $Value) = explode('=', $Var);
            $Call[$Key] = $Value;
        }

        return $Call;
    });