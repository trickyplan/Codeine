<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Write', function ($Call)
    {
        return htmlspecialchars_decode($Call['Value'], ENT_HTML5 | ENT_QUOTES);
    });

    setFn(['Read', 'Where'], function ($Call)
    {
        return htmlspecialchars_decode($Call['Value'], ENT_HTML5 | ENT_QUOTES);
    });