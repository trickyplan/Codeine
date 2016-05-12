<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */


    setFn('Get', function ($Call)
    {
        if (isset($Call['Driver']))
            return $Call['Prefix'].F::Run('Security.Entropy.'.$Call['Driver'], null, $Call);
        else
        {
            if (isset($Call['Modes'][$Call['Mode']]))
                return $Call['Prefix'].F::Live($Call['Modes'][$Call['Mode']], $Call);
            else
                return null;
        }
    });