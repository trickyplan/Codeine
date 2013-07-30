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

        $Call = F::Hook('beforeAcceptDo', $Call);

        $Call['Where'] = F::Live($Call['Where']); // FIXME

        return F::Run(null, $_SERVER['REQUEST_METHOD'], $Call);
    });

    setFn('GET', function ($Call)
    {
        $Call = F::Hook('beforeAcceptGet', $Call);

            $Call = F::Run('Entity.List', 'Do', $Call, ['Context' => 'app']);

        $Call = F::Hook('afterAcceptGet', $Call);

        return $Call;
    });

    setFn('POST', function ($Call)
    {
        $Call = F::Hook('beforeAcceptPost', $Call);

            F::Run('Entity', 'Update', $Call,
            [
                'One' => true,
                'Data' =>
                [
                    'Status' => 1,
                    'Moderated' => F::Run('System.Time', 'Get', $Call),
                    'Moderator' => $Call['Session']['User']['ID']
                ]
            ]);

        $Call = F::Hook('afterAcceptPost', $Call);

        return $Call;
    });
