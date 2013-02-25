<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    setFn('beforeList', function ($Call)
    {
        if (isset($Call['Request']['Filter']))
        {
            foreach ($Call['Request']['Filter'] as $Key => $Value)
                if (!empty($Value))
                    $Call['Where'][$Key] = $Value;
        }

        return $Call;
    });