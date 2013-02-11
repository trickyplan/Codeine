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

            $Call = F::Hook('beforeCheck', $Call);

            F::Run('Entity', 'Update', $Call,
            [
                'Data' =>
                [
                    'Check' => true,
                    'Checked' => F::Run('System.Time', 'Get', $Call),
                    'Checker' => $Call['Session']['User']['ID']
                ]
            ]);

        $Call = F::Run('System.Interface.Web', 'Redirect', $Call, ['Location' => $_SERVER['HTTP_REFERER']]);

        return $Call;
    });