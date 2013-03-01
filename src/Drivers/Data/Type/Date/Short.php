<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Write', function ($Call)
    {
        if (isset($Call['Value']) && !empty($Call['Value']) && $Call['Value']>0)
        {
            if (!is_numeric($Call['Value']))
            {
                $Date = strptime($Call['Value'],'%d.%m.%Y');
                return mktime(0,0,0, 1+$Date['tm_mon'], $Date['tm_mday'], $Date['tm_year']);
            }
            else
                return $Call['Value'];
        }
        else
            return null;

    });

    setFn('Read', function ($Call)
    {
        return $Call['Value'];
    });