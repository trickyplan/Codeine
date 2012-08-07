<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 7.6.2
     * @date 13.08.11
     * @time 22:37
     */

    self::setFn ('Open', function ($Call)
    {
        return json_decode(file_get_contents(F::findFile($Call['Filename'])), true);
    });

    self::setFn ('Read', function ($Call)
    {
        if (isset($Call['Where']['ID']))
            return $Call['Link'][$Call['Where']['ID']];
        else
            return $Call['Link'];
    });

    self::setFn ('Write', function ($Call)
    {

    });

    self::setFn ('Close', function ($Call)
    {
        return true;
    });