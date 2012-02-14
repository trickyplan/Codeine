<?php

    /* Codeine
     * @author BreathLess
     * @description: Simple HTML Renderer
     * @package Codeine
     * @version 7.1
     */

    self::setFn('Render', function ($Call)
    {
        $Call['Output'] = strip_tags($Call['Text']);
        return $Call;
    });
