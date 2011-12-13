<?php

    /* Codeine
     * @author BreathLess
     * @description: Simple HTML Renderer
     * @package Codeine
     * @version 6.0
     */

    self::setFn('Render', function ($Call)
    {
        $Call['Output'] = $Call['Text'];
        return $Call;
    });
