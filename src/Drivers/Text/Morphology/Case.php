<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Convert', function ($Call)
    {
        return F::Live($Call['Morphology']['Case Engine'], $Call);
    });