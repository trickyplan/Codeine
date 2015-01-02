<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Apply('Entity', 'Load', $Call);

        if (isset($Call['Where']))
            $Call['Where'] = F::Live($Call['Where']); // FIXME

        $Call = F::Hook('beforeAllowDo', $Call);

        if ($Call['Allow']['Mode'] == 'Confirmation')
            $Call = F::Run(null, $Call['HTTP']['Method'], $Call);
        elseif ($Call['Allow']['Mode'] == 'Direct')
            $Call = F::Run(null, 'POST', $Call);

        $Call = F::Hook('afterAllowDo', $Call);

        return $Call;
    });

    setFn('GET', function ($Call)
    {
        $Call = F::Hook('beforeAllowGet', $Call);

            $Call = F::Apply('Entity.List', 'Do', $Call, ['Context' => 'app']);

        $Call['Context'] = '';

        $Call = F::Hook('afterAllowGet', $Call);

        return $Call;
    });

    setFn('POST', function ($Call)
    {
        $Call = F::Hook('beforeAllowPost', $Call);

            $Call['Data'] = F::Run('Entity', 'Update', $Call,
            [
                'Data!' =>
                [
                    'Status' => 1,
                    'Moderated' => F::Run('System.Time', 'Get', $Call),
                    'Moderator' => $Call['Session']['User']['ID']
                ]
            ]);

        $Call = F::Hook('afterAllowPost', $Call);
        return $Call;
    });
