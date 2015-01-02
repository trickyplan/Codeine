<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Log', function ($Call)
    {
        if (isset($Call['Modes'][$Call['Mode']]))
            return F::Run($Call['Modes'][$Call['Mode']], null, $Call);
        else
            return null;
    });