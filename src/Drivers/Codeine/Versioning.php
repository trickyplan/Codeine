<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        self::loadOptions('Version');

        if (isset(self::$_Options['Project']['Version']['Codeine'])
            && self::$_Options['Project']['Version']['Codeine'] > self::$_Options['Version']['Codeine'])
            trigger_error('Codeine '.self::$_Options['Project']['Version']['Codeine'].'+ needed. Installed: '
                .self::$_Options['Version']['Codeine']);

        if (isset(self::$_Options['Version']['Codeine']))
        {
            self::Log('Codeine: *'.self::$_Options['Version']['Codeine'].'*', LOG_INFO);
        }

        if (isset(self::$_Options['Version']['Project']))
        {
            self::Log('Project: *'.self::$_Options['Version']['Project'].'*', LOG_INFO);
        }

        return $Call;
    });