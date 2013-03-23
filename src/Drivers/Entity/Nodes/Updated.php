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
            $Call['Data'] = F::Diff($Call['Data'], $Call['Current']);

        foreach ($Call['Current'] as $IX => &$Element)
            $Call['Data'][$IX]['ID'] = $Call['Current'][$IX]['ID'];

        return $Call;
    });