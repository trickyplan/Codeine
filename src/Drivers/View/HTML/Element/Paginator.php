<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Make', function ($Call)
    {
        $Call['FirstURL'] = preg_replace('@/page(\d+)@', '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

        if (empty($Call['PageURL']))
            $Call['PageURL'] = $Call['FirstURL'].'/page';

        // FIXME! Temporary COSTYL
        return F::Run('View.HTML.Element.Paginator.'.$Call['Method'], 'Make', $Call);
    });