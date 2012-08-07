<?php

    /* Codeine
     * @author BreathLess
     * @description
     * @package Codeine
     * @version 7.6.2
     */

    self::setFn('Write', function ($Call)
    {
        if (is_scalar($Call['Value']))
            return $Call['Value'];

        if ($Call['Value']['error'] == 0)
            return F::Run('IO', 'Write', array ('Storage' =>  'Upload', 'Scope' => $Call['Node']['Scope'], 'Value' => $Call['Value']));
        else
            return null;
    });

    self::setFn('Read', function ($Call)
    {
        return $Call['Value'];
    });