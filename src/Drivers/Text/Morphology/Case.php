<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Convert', function ($Call)
    {
        return F::Live($Call['Morphology']['Case Engine'], $Call);
    });