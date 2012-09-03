<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Read', function ($Call)
    {
        return F::Run($Call['Cache'], 'Read', $Call);
    });

    self::setFn('Write', function ($Call)
    {
        return $Call = F::Run($Call['Cache'], 'Write', $Call);
    });