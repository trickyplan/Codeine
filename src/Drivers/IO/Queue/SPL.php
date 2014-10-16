<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Open', function ($Call)
    {
        return new SplQueue();
    });

    setFn('Write', function ($Call)
    {
        $Call['Link']->enqueue($Call['Data']);
        return $Call;
    });

    setFn('Read', function ($Call)
    {
       return $Call['Link']->isEmpty()? null: $Call['Link']->dequeue();
    });