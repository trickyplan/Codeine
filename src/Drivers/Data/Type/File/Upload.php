<?php

    /* Codeine
     * @author BreathLess
     * @description
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Write', function ($Call)
    {
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