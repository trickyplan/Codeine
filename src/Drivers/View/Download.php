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

        $pi = pathinfo($Call['Output']['Content']);

        $Call['HTTP']['Headers']['Content-Disposition:'] =
            'attachment;filename="'.$Call['Output']['Title'].'.'.$pi['extension'].'"';

        readfile($Call['Output']['Content']);

        return $Call;
    });
