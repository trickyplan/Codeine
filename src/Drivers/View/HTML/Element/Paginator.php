<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Make', function ($Call)
    {
        return F::Run('View.HTML.Element.Paginator.'.$Call['Method'], 'Make', $Call);
    });