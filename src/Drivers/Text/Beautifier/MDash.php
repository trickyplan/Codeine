<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Process', function ($Call)
    {
        $Call['Value'] = preg_replace('/ - /', ' — ', $Call['Value']);

        return $Call;
     });