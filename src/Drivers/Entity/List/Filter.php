<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    setFn('beforeList', function ($Call)
    {
        if (isset($Call['Request']['Filter']) && is_array($Call['Request']['Filter']))
        {
            foreach ($Call['Request']['Filter'] as $Key => $Value)
                if (!empty($Value))
                    $Call['Where'][$Key] = $Value;
        }

        return $Call;
    });

    setFn('afterList', function ($Call)
    {
        if (isset($Call['Request']['Filter']) && is_array($Call['Request']['Filter']))
            unset($Call['Request']['Filter']);

        $Call['Where'] = [];
        return $Call;
    });