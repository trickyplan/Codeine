<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Restrictions
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 08.01.11
     * @time 1:52
     */

    self::Fn('Get', function ($Call)
    {
        if ($Call['Scheme']['Nodes'][$Call['Key']]['NoRead'])
            return null;
        else
            return $Call['Data'][$Call['Key']];
    });

    self::Fn('Set', function ($Call)
    {
        if ($Call['Scheme']['Nodes'][$Call['Key']]['NoWrite'])
            return null;
        else
            return $Call['Data'][$Call['Key']] = $Call['Value'];
    });
