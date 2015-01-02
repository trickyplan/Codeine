<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Add', function ($Call)
    {
        $Call['Output'][$Call['Name']][] = $Call['Widget'];
        return $Call;
    });