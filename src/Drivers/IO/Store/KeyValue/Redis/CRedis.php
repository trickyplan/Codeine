<?php

    /* Codeine
     * @author BreathLess
     * @description: Redis Driver
     * @package Codeine
     * @version 6.0
     */

    self::setFn('Open', function ($Call)
    {
        $Redis = new Redis();
        $Redis->connect($Call['URL'], $Call['Port']);

        return $Redis;
    });

    self::setFn('Load', function ($Call)
    {
        if (is_array($Call['ID']))
        {
            foreach ($Call['ID'] as &$ID)
                $ID = $Call['Scope'].':'.$ID;
            
            return json_decode($Call['Link']->getMultiple($Call['ID']), true) ;
        }
        else
        {
            if (null != ($Result = $Call['Link']->get($Call['Scope'].':'.$Call['ID'])))
                return json_decode($Result, true);
            else
                return null;
        }
    });

    self::setFn('Create', function ($Call)
    {
        return $Call['Link']->set($Call['Scope'].':'.$Call['ID'], json_encode($Call['Value']));
    });

    self::setFn('Update', function ($Call)
    {
        return $Call['Link']->set($Call['Scope'].':'.$Call['ID'], json_encode($Call['Value']));
    });

    self::setFn('Delete', function ($Call)
    {
        return $Call['Link']->delete($Call['Scope'].':'.$Call['ID']);
    });