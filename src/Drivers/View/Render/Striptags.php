<?php

    /* Codeine
     * @author BreathLess
     * @description: Simple HTML Renderer
     * @package Codeine
     * @version 6.0
     */

    self::setFn('Render', function ($Call)
    {
        $Call['Output'] = strip_tags($Call['Text']);
        return $Call;
    });
