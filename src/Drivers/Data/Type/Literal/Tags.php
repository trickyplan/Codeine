<?php

    /* Codeine
     * @author BreathLess
     * @description
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Write', function ($Call)
    {
        $Tags = explode(',', $Call['Value']);

        foreach ($Tags as &$Tag)
            $Tag = trim($Tag);

        return $Tags;
    });

    self::setFn('Read', function ($Call)
    {
        return $Call['Value'];
    });