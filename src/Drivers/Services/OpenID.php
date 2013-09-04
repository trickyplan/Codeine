<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Entry Point', function ($Call)
    {
        $Call['Output']['Content'][] =
            '<pre>'.json_encode($Call['Request']).'</pre>';

        return $Call;
    });