<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Process', function ($Call)
    {
        if (isset($Call['Node']['Required']) && $Call['Node']['Required'])
        {
            if (!isset($Call['Data'][$Call['Name']]) or empty($Call['Data'][$Call['Name']]))
                return 'Required';
        }
        return true;
    });