<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Apply('Entity', 'Load', $Call);

        $Call = F::Hook('beforeDeclineDo', $Call);

        $Call['Where'] = F::Live($Call['Where']); // FIXME

        return F::Run(null, $_SERVER['REQUEST_METHOD'], $Call);
    });

    setFn('GET', function ($Call)
    {
        $Call = F::Hook('beforeDeclineGet', $Call);

        $Call = F::Apply('Entity.List', 'Do', $Call);

        $Call = F::Hook('afterDeclineGet', $Call);

        return $Call;
    });

    setFn('POST', function ($Call)
    {
        $Call = F::Hook('beforeDeclinePost', $Call);

            F::Run('Entity', 'Update', $Call,
            [
                'Data' =>
                [
                    'Status' => -1,
                    'Moderated' => F::Run('System.Time', 'Get', $Call),
                    'Moderator' => $Call['Session']['User']['ID']
                ]
            ]);

        $Call = F::Hook('afterDeclinePost', $Call);

        return $Call;
    });
