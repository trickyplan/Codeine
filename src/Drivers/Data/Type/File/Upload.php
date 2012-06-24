<?php

    /* Codeine
     * @author BreathLess
     * @description
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn('Write', function ($Call)
    {
        if (is_scalar($Call['Value']))
            return $Call['Value'];

        // FIXME Scope Support
        if ($Call['Value']['error'] == 0)
            return F::Run('IO', 'Write', array ('Storage' =>  'Upload', 'Value' => $Call['Value']));
        else
            return null;
    });

    self::setFn('Read', function ($Call)
    {
        return $Call['Value'];
    });