<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */


    setFn('Get', function ($Call)
    {
        if (isset($Call['Modes'][$Call['Mode']]))
            ;
        else
            $Call['Mode'] = 'Normal';

        return F::Live($Call['Modes'][$Call['Mode']], $Call)+F::Dot($Call, 'Time.Offset');
    });