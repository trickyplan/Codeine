<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        if ($Call['Return'] == $Call['Case']['Result']['Equal'])
            $Decision = true;
        else
        {
            $Call['Failure'] = true;
            $Decision = false;
            F::Log(j($Call['Return']).' is not equal '.j($Call['Case']['Result']['Equal']), LOG_WARNING, 'Developer');
        }

        return [$Decision, print_r($Call['Return'], true)];
    });