<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Hash', function ($Call)
    {
        $Call['IOHash'] =
            sha1(serialize([$Call['Storage'],isset($Call['Scope'])? $Call['Scope']: '',$Call['Where']]));

        return $Call;
    });

    setFn('beforeIORead', function ($Call)
    {
        if (isset($Call['Where']) && !isset($Call['ReRead']))
        {
            $Call = F::Apply(null, 'Hash', $Call);

            if (($Result = F::Get($Call['IOHash'])) !== null)
            {
                F::Log($Call['IOHash'].' deduped', LOG_GOOD);
                $Call['Result'] = $Result;
            }
            else
                F::Log($Call['IOHash'].' missed', LOG_DEBUG);
        }

        unset($Call['ReRead']);

        return $Call;
    });

    setFn('beforeIOWrite', function ($Call)
    {
        if (isset($Call['Where']))
            $Call = F::Apply(null, 'Hash', $Call);

        return $Call;
    });

    setFn('afterIORead', function ($Call)
    {
        if (isset($Call['IOHash']))
            F::Set($Call['IOHash'], $Call['Result']);

        return $Call;
    });

    setFn('afterIOWrite', function ($Call)
    {
        if (isset($Call['IOHash']))
            F::Set($Call['IOHash'], $Call['Data']);

        return $Call;
    });