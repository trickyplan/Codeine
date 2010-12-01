<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Lat-Lon
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 01.12.10
     * @time 20:14
     */

    self::Fn('Input', function ($Call)
    {
        return implode(';',$Call['Value']);
    });

    self::Fn('Output', function ($Call)
    {
        $Output = array();
        list ($Output['Lat'], $Output['Lon']) = explode(';',$Call['Value']);
        return $Output;
    });
    
    self::Fn('Validate', function ($Call)
    {
        return is_array($Call['Value']) && isset($Call['Value']['Lat']) && isset($Call['Value']['Lon']);
    });

    self::Fn('String', function ($Call)
    {
        return implode(' ',$Call['Value']);
    });

    self::Fn('VectorCompare', function ($Call)
    {
        // TODO Hypo
    });