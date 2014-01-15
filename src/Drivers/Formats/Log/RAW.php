<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Write', function ($Call)
    {
        $Output = $Call['Data'];

        $Output = preg_replace('/\*(.*)\*/SsUu', '$1', $Output);

        return $Output;
    });