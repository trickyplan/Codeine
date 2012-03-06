<?php

    /* Codeine
     * @author BreathLess
     * @description Data.Types.Input
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Process', function ($Call)
    {
        if (isset($Call['Data'][$Call['Name']]))
        {
            if (isset($Call['Node']['Type']))
                $Call['Data'][$Call['Name']] = F::Run('Entity.Nodes.Type.'. $Call['Node']['Type'], $Call['Method']);

        }
        else
            return null;
    });