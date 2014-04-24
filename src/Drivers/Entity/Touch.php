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

            $Results = F::Run('Entity', 'Update', $Call, ['One' => false]);

            $Call['Output']['Content'][] =
                [
                    'Type' => 'Block',
                    'Value' => count($Results).' touched'
                ];

        $Call = F::Hook('afterTouch', $Call);

        return $Call;
    });