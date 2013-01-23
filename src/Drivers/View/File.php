<?php

    /* Codeine
     * @author BreathLess
     * @description: Simple HTML Renderer
     * @package Codeine
     * @version 7.x
     */

    setFn('Render', function ($Call)
    {
        $Call['Headers']['Content-Disposition:'] = 'attachment;filename="'.urlencode($Call['Title']).'"';

        $Call['Output'] = file_get_contents($Call['Output']['Content']);

        return $Call;
    });
