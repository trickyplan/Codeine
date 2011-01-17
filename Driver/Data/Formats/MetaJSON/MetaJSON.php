<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Extended JSON
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 23.11.10
     * @time 23:26
     */

    self::Fn('Encode', function ($Call)
    {
        return json_encode($Call['Input']);
    });

    self::Fn('Decode', function ($Call)
    {
        $Data = json_decode($Call['Input'], true);
        $Includes = array();

        foreach ($Data['#Includes'] as $Name)
        {
            $Includes[] = json_decode(
                        Data::Read($Name), true
                    );
        }

        unset($Data['#Includes']);
        foreach ($Includes as $Include)
            $Data = array_merge_recursive($Data, $Include);

        return $Data;
    });