<?php

    /* Codeine
     * @author BreathLess
     * @description: Simple HTML Renderer
     * @package Codeine
     * @version 7.x
     */

    setFn('Render', function ($Call)
    {
        $Call['Headers']['Content-type:'] = 'application/octet-stream';
        $Call['Headers']['Accept-Ranges:'] = 'bytes';
        $Call['Headers']['Connection:'] = 'keep-alive';

        $Call['Output'] = file_get_contents($Call['Output']['File']);

        return $Call;
    });
