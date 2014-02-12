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

            $Call['Data'] = F::Run('Entity', 'Read', $Call);
            $Results = F::Run('Entity', 'Update', $Call);

            foreach ($Results as $Result)
                $Call['Output']['Content'][] =
                    [
                        'Type' => 'Template',
                        'Scope' => $Call['Entity'].'/'.(isset($Call['Scope'])? $Call['Scope']: ''),
                        'ID' => 'Show/Short',
                        'Data' => $Result
                    ];

        $Call = F::Hook('afterTouch', $Call);

        return $Call;
    });