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

            $Call = F::Run(null, $_SERVER['REQUEST_METHOD'], $Call);

        $Call = F::Hook('afterDeleteDo', $Call);

        return $Call;
    });

    setFn('GET', function ($Call)
    {
        $Call = F::Hook('beforeDeleteGet', $Call);

            $Call['Layouts'][] = ['Scope' => $Call['Entity'],'ID' => 'Main','Context' => $Call['Context']];
            $Call = F::Run('Entity.List', 'Do', $Call, ['Context' => 'app']);

        $Call = F::Hook('afterDeleteGet', $Call);

        return $Call;
    });

    setFn('POST', function ($Call)
    {
        $Call = F::Hook('beforeDeletePost', $Call);

            $Call = F::Run('Entity', 'Delete', $Call);

        $Call = F::Hook('afterDeletePost', $Call);

        return $Call;
    });