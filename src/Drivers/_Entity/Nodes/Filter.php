<?php

    /* Codeine
     * @author BreathLess
     * @description Default value support 
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Process', function ($Call)
    {
        foreach ($Call['Nodes'] as $Name => $Node)
            if (isset($Node['Filter']))
                foreach ($Node['Filter'] as $Filter)
                    $Call['Data'][$Name] = F::Live($Filter, array('Value' => $Call['Data'][$Name]));

        return $Call;
    });