<?php

    /* Codeine
     * @author BreathLess
     * @description JSONT Implementation 
     * @package Codeine
     * @version 6.0
     */

    self::setFn ('Process', function ($Call)
    {
        $Rules = $Call['Rules'];

        array_walk_recursive($Call['Value'],
            function (&$Value, $Key) use ($Rules)
            {
                if (isset($Rules[$Key]))
                    $Value = $Rules[$Key]($Value);
            }
        );

        return $Call['Value'];
    });