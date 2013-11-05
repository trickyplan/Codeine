<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    setFn('beforeOperation', function ($Call)
    {
        if (isset($Call['List']['Sort']))
            $Call['Sort'] = $Call['List']['Sort'];

        if (isset($Call['Request']['sort']) && isset($Call['Nodes'][$Call['Request']['sort']]))
            $Call['Sort'] = [$Call['Request']['sort'] => true];
        else
        {
            if(isset($Call['Request']['rsort']) && isset($Call['Nodes'][$Call['Request']['rsort']]))
                $Call['Sort'] = [$Call['Request']['rsort'] => false];
        }

        return $Call;
    });