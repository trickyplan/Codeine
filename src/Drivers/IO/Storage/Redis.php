<?php

    /* Codeine
     * @author BreathLess
     * @description XCache Data Driver
     * @package Codeine
     * @version 7.x
     */

    setFn ('Open', function ($Call)
    {
        $Redis = new Redis();
        $Redis->pconnect ($Call['Server'], $Call['Port']);

        return $Redis;
    });

    setFn ('Read', function ($Call)
    {
        if (is_array($Call['Where']['ID']))
        {
            foreach ($Call['Where']['ID'] as &$ID)
                $ID = $Call['Scope'].'.'.$ID;

            F::Log('Redis Read: '.$Call['Where']['ID'], LOG_INFO, 'Administrator');
            return $Call['Link']->mGet($Call['Where']['ID']);
        }
        else
        {
            F::Log('Redis Read: '.$Call['Where']['ID'], LOG_INFO, 'Administrator');

            if (($Result = $Call['Link']->get($Call['Scope'].'.'.$Call['Where']['ID']))  !== false)
                return [$Result];
            else
                return null;
        }
    });

    setFn ('Write', function ($Call)
    {
        if (isset($Call['Where']))
        {
            if (null === $Call['Data'])
            {
                F::Log('Redis Delete: '.$Call['Where']['ID'], LOG_INFO, 'Administrator');
                $Call['Link']->del($Call['Scope'].'.'.$Call['Where']['ID']);
            }
            else
            {
                F::Log('Redis Update: '.$Call['Where']['ID'], LOG_INFO, 'Administrator');
                $Call['Link']->set($Call['Scope'].'.'.$Call['Where']['ID'], $Call['Data'], $Call['TTL']);
            }
        }
        else
        {
            F::Log('Redis Create: '.j($Call['Data']), LOG_INFO, 'Administrator');
            $Call['Link']->set($Call['Scope'].$Call['ID'], $Call['Data'], $Call['TTL']);
        }

        return $Call['Data'];
    });

    setFn ('Close', function ($Call)
    {
        return true;
    });

    setFn ('Execute', function ($Call)
    {
        return true;
    });

    setFn ('Exist', function ($Call)
    {
        return $Call['Link']->exists ($Call['Scope'].$Call['Where']['ID']);
    });

    setFn('Status', function ($Call)
    {
       $Info = $Call['Link']->info();
       foreach ($Info as $Key => &$Value)
           $Value = [$Key, $Value];

       return $Info;
    });