<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Write', function ($Call)
    {
        $Output = $Call['Data'][0];
        $Output = preg_replace('/\*(.*)\*/SsUu', '$1', implode(PHP_EOL, (array) $Output));
        return $Output;
    });