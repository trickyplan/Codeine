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

        $Call['Data'] = F::Run('Entity', 'Read', $Call);

        $Call = F::Hook('beforeCheck', $Call);

        foreach ($Call['Data'] as $Element)
            F::Run('Entity', 'Update', $Call,
            [
                'Where' => $Element['ID'],
                'One' => true,
                'Data' =>
                [
                    'Check' => true,
                    'Checker' => $Call['Session']['User']['ID'],
                    'Checked' => time()
                ]
            ]);

        $Call = F::Hook('afterCheck', $Call);

        return $Call;
    });