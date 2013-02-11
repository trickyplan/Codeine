<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Run('Entity', 'Load', $Call);

        $Call = F::Hook('beforeRejectDo', $Call);

        $Call['Where'] = F::Live($Call['Where']); // FIXME

        return F::Run(null, $_SERVER['REQUEST_METHOD'], $Call);
    });

    setFn('GET', function ($Call)
    {
        $Call = F::Hook('beforeRejectGet', $Call);

        $Call['Layouts'][] = ['Scope' => $Call['Entity'],'ID' => 'Main','Context' => $Call['Context']];
        $Call = F::Run('Entity.Show.Static', 'Do', $Call, ['Template' => 'Check', 'Context' => 'app']);

        $Call = F::Hook('afterRejectGet', $Call);

        return $Call;
    });

    setFn('POST', function ($Call)
    {
        $Call = F::Hook('beforeRejectPost', $Call);

            F::Run('Entity', 'Update', $Call,
            [
                'Data' =>
                [
                    'Status' => -1,
                    'Moderated' => F::Run('System.Time', 'Get', $Call),
                    'Moderator' => $Call['Session']['User']['ID']
                ]
            ]);

        $Call = F::Hook('afterRejectPost', $Call);

        return $Call;
    });
