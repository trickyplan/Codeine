<?php

    /* Codeine
     * @author BreathLess
     * @description Default value support 
     * @package Codeine
     * @version 7.x
     */

    setFn('Process', function ($Call)
    {
        if (isset($Call['Data']))
        {
            foreach ($Call['Data'] as $IX => &$Element)
                $Element = array_diff_assoc($Element, $Call['Current'][$IX]);
        }

        return $Call;
    });