<?php

    /* Codeine
     * @author BreathLess
     * @description XCache Data Driver
     * @package Codeine
     * @version 7.6.2
     */

    self::setFn ('Open', function ($Call)
    {
        $Redis = new Redis();
        $Redis->connect ($Call['Server'], $Call['Port']);
        $Redis->setOption (Redis::OPT_SERIALIZER, Redis::SERIALIZER_IGBINARY); // FIXME

        return $Redis;
    });

    self::setFn ('Read', function ($Call)
    {
        if (is_array($Call['Where']['ID']))
        {
            foreach ($Call['Where']['ID'] as &$ID)
                $ID = $Call['Scope'].$ID;

            return $Call['Link']->mGet($Call['Where']['ID']);
        }
        else
            if (($Result = $Call['Link']->get($Call['Scope'].$Call['Where']['ID'])) !== false)
                return array($Result);
            else
                return null;
    });

    self::setFn ('Write', function ($Call)
    {
        if (isset($Call['Where']))
        {
            if (null === $Call['Data'])
                $Call['Link']->del($Call['Scope'].$Call['Where']['ID']);
            else
            {
                $Call['Data'] = F::Merge(F::Run(null, 'Read', $Call)[0], $Call['Data']);
                $Call['Link']->set($Call['Scope'].$Call['Where']['ID'], $Call['Data'], $Call['TTL']);
            }
        }
        else
        {
            $Call['Link']->set($Call['Scope'].$Call['Data']['ID'], $Call['Data'], $Call['TTL']);
            F::Log($Call['Data']);
        }

        return $Call['Data'];
    });

    self::setFn ('Close', function ($Call)
    {
        return true;
    });

    self::setFn ('Execute', function ($Call)
    {
        return true;
    });

    self::setFn ('Exist', function ($Call)
    {
        return $Call['Link']->exists ($Call['Scope'].$Call['Where']['ID']);
    });

    self::setFn('Status', function ($Call)
    {
       $Info = $Call['Link']->info();
       foreach ($Info as $Key => &$Value)
           $Value = array($Key, $Value);

       return $Info;
    });