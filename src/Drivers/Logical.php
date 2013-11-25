<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Equal', function ($Call)
    {
        return F::Live($Call['A']) == F::Live($Call['B']);
    });

    setFn('Greater', function ($Call)
    {
        return F::Live($Call['A']) > F::Live($Call['B']);
    });

    setFn('Lesser', function ($Call)
    {
        return F::Live($Call['A']) < F::Live($Call['B']);
    });