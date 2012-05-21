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
            if (F::isCall($Node) && !isset($Call['Data'][$Name]))
                $Call['Data'][$Name] = F::Live($Node, $Call, array('Node' => $Name));

        return $Call;
    });