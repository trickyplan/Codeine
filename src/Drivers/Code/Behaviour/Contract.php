<?php

    /* Codeine
     * @author BreathLess
     * @description: Contract Behaviour
     * @package Codeine
     * @version 6.0
     * @date 10.07.11
     * @time 1:09
     */

    self::setFn('beforeRun', function ($Call)
    {
        foreach ($Call['Contractors'] as $Contractor)
        {
            $Call['Value'] = F::Run(
                array(
                    '_N' => $Call['_N'].'.'.$Contractor,
                    '_F' => 'Run',
                    'Value' => $Call['Value'],
                    'NoBehaviours' => true
                )
            );
        }
        return $Call['Value'];
    });

    self::setFn('afterRun', function ($Call)
    {
        return $Call;
    });
