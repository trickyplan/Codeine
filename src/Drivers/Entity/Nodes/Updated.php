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
                $Element = F::Diff($Call['Current'][$IX], $Element);
        }
        return $Call;
    });