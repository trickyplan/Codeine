<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Process', function ($Call)
    {
        $Call['Value'] = preg_replace('/ - /', '&nbsp;&#8212; ', $Call['Value']);

        return $Call;
     });