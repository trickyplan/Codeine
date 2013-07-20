<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        self::loadOptions('Version');

        if (isset(self::$_Options['Project']['Version']['Codeine'])
            && self::$_Options['Project']['Version']['Codeine'] > self::$_Options['Version']['Codeine']['Major'])
            trigger_error('Codeine '.self::$_Options['Project']['Version']['Codeine'].'+ needed. Installed: '
                .self::$_Options['Version']['Codeine']['Major']);

        if (isset(self::$_Options['Version']['Codeine']))
        {
            self::Log('Codeine: '.self::$_Options['Version']['Codeine']['Major'], LOG_INFO);
            self::Log('Build: '.self::$_Options['Version']['Codeine']['Minor'], LOG_INFO);
        }

        if (isset(self::$_Options['Version']['Project']))
        {
            self::Log('Project: '.self::$_Options['Version']['Project']['Major'], LOG_INFO);
            self::Log('Build: '.self::$_Options['Version']['Project']['Minor'], LOG_INFO);
        }

        return $Call;
    });