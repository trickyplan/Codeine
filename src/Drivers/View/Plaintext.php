<?php

    /* Codeine
     * @author BreathLess
     * @description: Simple HTML Renderer
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Render', function ($Call)
    {
        $Call['Headers']['Content-type:'] = 'text/plain';
        return $Call;
    });
