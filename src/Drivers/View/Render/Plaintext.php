<?php

    /* Codeine
     * @author BreathLess
     * @description: Simple HTML Renderer
     * @package Codeine
     * @version 7.0
     */

    self::setFn('Render', function ($Call)
    {
        $Call['Output'] = $Call['Value'];
        return $Call;
    });
