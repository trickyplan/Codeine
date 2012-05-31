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
        return F::Run('IO', 'Write', array ('Storage' =>  'Upload', 'Value' => $Call['Value']));
    });

    self::setFn('Read', function ($Call)
    {
        return $Call['Value'];
    });

    self::setFn('Widget', function ($Call)
    {
                return $Call['Widgets'];
    });