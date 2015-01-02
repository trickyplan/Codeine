<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 8.x
     * @date 13.08.11
     * @time 22:37
     */

    setFn ('Open', function ($Call)
    {
        return jd(file_get_contents(F::findFile($Call['Filename'])), true);
    });

    setFn ('Read', function ($Call)
    {
        if (isset($Call['Where']['ID']))
            return $Call['Link'][$Call['Where']['ID']];
        else
            return $Call['Link'];
    });

    setFn ('Write', function ($Call)
    {

    });

    setFn ('Close', function ($Call)
    {
        return true;
    });