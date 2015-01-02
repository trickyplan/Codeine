<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Write', function ($Call)
    {
        $Call['Value'] = strip_tags($Call['Value'], $Call['Allowed Tags']);

        return htmlspecialchars_decode($Call['Value'], ENT_HTML5 | ENT_QUOTES);
    });

    setFn(['Read', 'Where'], function ($Call)
    {
        return htmlspecialchars_decode($Call['Value'], ENT_HTML5 | ENT_QUOTES);
    });