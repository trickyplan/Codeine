<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('And', function ($Call)
    {
        return $Call['A'] && $Call['B'];
    });

    setFn('Or', function ($Call)
    {
        return $Call['A'] || $Call['B'];
    });

    setFn('Xor', function ($Call)
    {
        return $Call['A'] xor $Call['B'];
    });

    setFn('Not', function ($Call)
    {
        return !$Call['A'] && $Call['B'];
    });