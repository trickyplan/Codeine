<?php

    /* Codeine
     * @author BreathLess
     * @description: Random integer
     * @package Codeine
     * @version 7.x
     * @date 04.12.10
     * @time 14:56
     */

    setFn('Get', function ($Call)
    {
        if (isset($Call['Where']))
            $Call['Where'] = F::Live($Call['Where']);

        return F::Run('Entity', 'Count', $Call)+$Call['Increment'];
    });
