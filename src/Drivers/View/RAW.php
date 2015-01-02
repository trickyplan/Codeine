<?php

    /* Codeine
     * @author BreathLess
     * @description: Simple HTML Renderer
     * @package Codeine
     * @version 8.x
     */

    setFn('Render', function ($Call)
    {
        $finfo = new finfo(FILEINFO_MIME);
        $Call['Output'] = implode($Call['Output']['Content']);
        $Call['HTTP']['Headers']['Content-type:'] = $finfo->buffer($Call['Output']);

        return $Call;
    });
