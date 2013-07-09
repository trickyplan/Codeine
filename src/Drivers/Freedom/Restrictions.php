<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        if (isset($Call['Freedom']['URLs'][$_SERVER['REQUEST_URI']]))
        {
            $Call = F::Run('Error.451', 'Page', $Call);
            // TODO Country test
        }

        return $Call;
    });