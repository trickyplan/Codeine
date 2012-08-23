<?php

    /* Codeine
     * @author BreathLess
     * @description Default value support 
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Process', function ($Call)
    {
        foreach ($Call['Nodes'] as $Name => $Node)
        {
            if (isset($Node['afterRead']))
                foreach ($Call['Data'] as &$Row)
                    foreach ($Node['afterRead'] as $Hook)
                        $Row[$Name] = F::Live($Hook, array('Value'=> $Row[$Name]));
        }

        return $Call;
    });