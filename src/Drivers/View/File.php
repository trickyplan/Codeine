<?php

    /* Codeine
     * @author BreathLess
     * @description: Simple HTML Renderer
     * @package Codeine
     * @version 7.x
     */

    setFn('Render', function ($Call)
    {
        $Call['Headers']['Content-type:'] = mime_content_type($Call['Output']['File']);

        readfile($Call['Output']['File']);
        $Call['Output'] = '';

        return $Call;
    });
