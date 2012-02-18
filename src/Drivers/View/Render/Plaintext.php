<?php

    /* Codeine
     * @author BreathLess
     * @description: Simple HTML Renderer
     * @package Codeine
     * @version 7.1
     */

    self::setFn('Render', function ($Call)
    {
        $Call['Output'] = $Call['Output'];
        return $Call;
    });
