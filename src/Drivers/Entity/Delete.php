<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4
     */

    setFn('Before', function ($Call)
    {
        $Call['Where'] = F::Live($Call['Where']);

        $Call['Data'] = F::Run('Entity', 'Read', $Call, ['ReRead' => true]);

        return $Call;
    });

    setFn('Do', function ($Call)
    {
        $Call = F::Hook('beforeDeleteDo', $Call);

            $Call = F::Apply(null, $Call['HTTP']['Method'], $Call);

        $Call = F::Hook('afterDeleteDo', $Call);

        return $Call;
    });

    setFn('GET', function ($Call)
    {
        $Call = F::Hook('beforeDeleteGet', $Call);

            $Call = F::Apply('Entity.List', 'Do', $Call, ['Context' => 'app']);

        $Call['Context'] = '';

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