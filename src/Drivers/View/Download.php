<?php

    /* Codeine
     * @author BreathLess
     * @description: Simple HTML Renderer
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Render', function ($Call)
    {
        $Call['Headers']['Content-type:'] = mime_content_type($Call['Output']['File']);

        $pi = pathinfo($Call['Output']['File']);
        $Call['Headers']['Content-Disposition:'] = 'attachment;filename="'.$Call['Output']['Title'].'.'.$pi['extension'].'"';

        $Call['Output'] = file_get_contents($Call['Output']['File']);
        return $Call;
    });
