<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: CreateEvent Fixer
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 26.02.11
     * @time 21:03
     */

    self::Fn('Catch', function ($Call)
    {
        $HookPath = Engine.Core::OptionsPath.'/Hooks.json';
        
        $Configuration = json_decode(file_get_contents($HookPath), true);

        list($Namespace, $Keys) = explode('::', $Call['Data']['NewEvent']);
        $Keys = explode('.', $Keys);

        $Value = $Configuration['Hooks'][$Namespace];
        
        foreach ($Keys as $Key)
        {
            if (!isset($Value[$Key]))
                $Value[$Key] = array();
            $Value = $Value[$Key];
        }

        file_put_contents($HookPath, json_encode($Configuration));
        
        // FIXME Data::Read
        return null;
    });
