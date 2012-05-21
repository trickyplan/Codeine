<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Read', function ($Call)
    {
        if (isset($Call['Where']))
            if (($Cached = F::Get('IO.'.$Call['Storage'].sha1(json_encode($Call['Where']))) !== null))
            {
                $Call['Data'] = $Cached;
                $Call['Cache.Hit'] = true;
            }

        return $Call;
    });

    self::setFn('Write', function ($Call)
    {
        if (isset($Call['Where']))
            if (!isset($Call['Cache.Hit']))
                F::Set('IO.'.$Call['Storage'].sha1(json_encode($Call['Where'])), $Call['Data']);

        return $Call;
    });