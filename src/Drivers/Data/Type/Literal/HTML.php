<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Write', function ($Call)
    {
        return htmlspecialchars($Call['Value'], ENT_HTML5 | ENT_QUOTES, 'UTF-8', false); // FIXME
    });

    setFn('Read', function ($Call)
    {
        return htmlspecialchars_decode($Call['Value'], ENT_HTML5 | ENT_QUOTES);
    });