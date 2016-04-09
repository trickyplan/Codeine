<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        if ($Call['Case']['Result']['Actual'] == $Call['Case']['Assert']['Equal'])
            $Decision = true;
        else
        {
            $Call['Failure'] = true;
            $Decision = false;
            F::Log(j($Call['Case']['Result']['Actual']).' is not equal '.j($Call['Case']['Assert']['Equal']), LOG_WARNING, 'Developer');
        }

        return $Decision;
    });