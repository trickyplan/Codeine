<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Apply('Entity', 'Load', $Call);

        $Call = F::Hook('beforeDisallowDo', $Call);

        $Call['Where'] = F::Live($Call['Where']); // FIXME

        return F::Run(null, $Call['HTTP']['Method'], $Call);
    });

    setFn('GET', function ($Call)
    {
        $Call = F::Hook('beforeDisallowGet', $Call);

            $Call = F::Apply('Entity.List', 'Do', $Call);

        $Call = F::Hook('afterDisallowGet', $Call);

        return $Call;
    });

    setFn('POST', function ($Call)
    {
        $Call = F::Hook('beforeDisallowPost', $Call);

            $Call['Data'] = F::Run('Entity', 'Update', $Call,
            [
                'Data' =>
                [
                    'Verdict' => $Call['Request']['Verdict'],
                    'Status' => -1,
                    'Moderated' => F::Run('System.Time', 'Get', $Call),
                    'Moderator' => $Call['Session']['User']['ID']
                ]
            ]);

        $Call = F::Hook('afterDisallowPost', $Call);

        return $Call;
    });
