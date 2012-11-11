<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Make', function ($Call)
    {
        if (empty($Call['PageURL']))
            $Call['PageURL'] = preg_replace('@/page(\d+)@', '', $_SERVER['REQUEST_URI']).'/page';
        // FIXME! Temporary COSTYL
        return F::Run('View.HTML.Element.Paginator.'.$Call['Method'], 'Make', $Call);
    });