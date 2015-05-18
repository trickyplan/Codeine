<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description XCache Data Driver
     * @package Codeine
     * @version 8.x
     */

    setFn ('Open', function ($Call)
    {
        $Result = false;
        $Redis = new Redis();

        if (isset($Call['Socket']))
        {
            if ($Result = $Redis->popen ($Call['Socket']))
                F::Log('Connect to socket '.$Call['Socket'], LOG_INFO, 'Administrator');
            else
                F::Log('No connection to socket '.$Call['Socket'], LOG_ERR, 'Administrator');
        }
        else
        {
            if ($Result = $Redis->popen ($Call['Server'], $Call['Port']))
                F::Log('Connect to '.$Call['Server'].':'.$Call['Port'], LOG_INFO, 'Administrator');
            else
                F::Log('No connection to '.$Call['Server'].':'.$Call['Port'], LOG_ERR, 'Administrator');
        }

        if ($Result)
            return $Redis;
        else
            return null;
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
       return $Call['Link']->info();
    });

    setFn('DBSize', function ($Call)
    {
        return $Call['Link']->dbSize();
    });