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

        $Call = F::Hook('beforeTouch', $Call);

        $Results = F::Run('Entity', 'Update', $Call, ['Data!' => ['Modified' => time()]]);

        foreach ($Results as $Result)
            $Call['Output']['Content'][] =
                [
                    'Type' => 'Template',
                    'Scope' => $Call['Entity'],
                    'ID' => 'Show/Short',
                    'Data' => $Result
                ];

        $Call = F::Hook('afterTouch', $Call);

        return $Call;
    });