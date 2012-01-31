<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.0
     */

    self::setFn ('Process', function ($Call)
    {
        if (isset($Call['Map']) && is_array($Call['Map']))
            foreach ($Call['Map'] as $Search => $Replace)
                $Call['Value'] = str_replace($Search, $Replace, $Call['Value']);

        return $Call['Value'];
     });