<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Open', function ($Call)
    {
        return new SplQueue();
    });

    self::setFn('Write', function ($Call)
    {
        $Call['Link']->enqueue($Call['Data']);
        return $Call;
    });

    self::setFn('Read', function ($Call)
    {
       return $Call['Link']->isEmpty()? null: $Call['Link']->dequeue();
    });