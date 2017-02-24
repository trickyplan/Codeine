<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 7.4
     */

    setFn('Before', function ($Call)
    {
        if (isset($Call['Where']))
            $Call['Where'] = F::Live($Call['Where'], $Call);
        else
            $Call = F::Hook('beforeDeleteAll', $Call);

        $Call['Current'] = F::Run('Entity', 'Read', $Call, ['Time' => microtime(true)]);

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

        if (isset($Call['Where']))
            $Call = F::Apply('Entity.List', 'Do', $Call, ['Context' => 'app', 'Template' => 'Delete']);

        $Call['Context'] = '';

        $Call = F::Hook('afterDeleteGet', $Call);

        return $Call;
    });

    setFn('POST', function ($Call)
    {
        $Call = F::Hook('beforeDeletePost', $Call);

            $Call['Data'] = F::Apply('Entity', 'Delete', $Call);

        $Call = F::Hook('afterDeletePost', $Call);

        return $Call;
    });