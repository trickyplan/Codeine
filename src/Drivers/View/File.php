<?php

    /* Codeine
     * @author BreathLess
     * @description: Simple HTML Renderer
     * @package Codeine
     * @version 7.x
     */

    setFn('Render', function ($Call)
    {
        $Call['HTTP']['Headers']['Content-type:'] = mime_content_type($Call['Output']['Content']);

        readfile($Call['Output']['Content']);
        $Call['Output'] = '';

        return $Call;
    });
