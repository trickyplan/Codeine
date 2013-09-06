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
            $Call['IOHash'] = sha1(serialize([$Call['Storage'],$Call['Scope'],$Call['Where']]));

            if (($Result = F::Get($Call['IOHash'])) !== null)
            {
                F::Log($Call['IOHash'].' deduped', LOG_GOOD);
                $Call['Result'] = $Result;
            }
        }

        unset($Call['ReRead']);
        return $Call;
    });

    setFn('afterIORead', function ($Call)
    {
        if (isset($Call['IOHash']))
            F::Set($Call['IOHash'], $Call['Result']);

        return $Call;
    });