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
            $Call['PageURL'] = $Call['FirstURL'].'/page';

        // FIXME! Temporary COSTYL
        return F::Run('View.HTML.Element.Paginator.'.$Call['Method'], 'Make', $Call);
    });