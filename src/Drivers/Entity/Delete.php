<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Hook('beforeDeleteDo', $Call);

        if (isset($Call['Where']))
            $Call['Where'] = F::Live($Call['Where']); // FIXME

            $Call = F::Apply(null, $_SERVER['REQUEST_METHOD'], $Call);

        $Call = F::Hook('afterDeleteDo', $Call);

        return $Call;
    });

    setFn('GET', function ($Call)
    {
        $Call = F::Hook('beforeDeleteGet', $Call);

            $Call = F::Apply('Entity.List', 'Do', $Call);

        $Call = F::Hook('afterDeleteGet', $Call);

        return $Call;
    });

    setFn('POST', function ($Call)
    {
        $Call = F::Hook('beforeDeletePost', $Call);

            $Call = F::Apply('Entity', 'Delete', $Call);

        $Call = F::Hook('afterDeletePost', $Call);

        return $Call;
    });