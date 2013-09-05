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

        $pi = pathinfo($Call['Output']['File']);

        $Call['Headers']['Content-Disposition:'] =
            'attachment;filename="'.$Call['Output']['Title'].'.'.$pi['extension'].'"';

        readfile($Call['Output']['File']);

        $Call['Output'] = '';
        return $Call;
    });
