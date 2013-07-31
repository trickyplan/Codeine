<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('beforeIORead', function ($Call)
    {
        if (isset($Call['Where']) && !isset($Call['ReRead']))
        {
            $IOHash = sha1(serialize([$Call['Storage'],$Call['Scope'], $Call['Where']]));

            if (($Result = F::Get($IOHash)) !== null)
                $Call['Result'] = $Result;
        }
        return $Call;
    });

    setFn('afterIORead', function ($Call)
    {
        if (isset($Call['Where']))
        {
            $IOHash = sha1(serialize([$Call['Storage'],$Call['Scope'], $Call['Where']]));
            F::Set($IOHash, $Call['Result']);
        }

        return $Call;
    });