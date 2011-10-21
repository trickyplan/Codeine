<?php

    /* Codeine
     * @author BreathLess
     * @description: Contract Behaviour
     * @package Codeine
     * @version 6.0
     * @date 10.07.11
     * @time 1:09
     */

    self::Fn('beforeRun', function ($Call)
    {
        $Contractors = F::Options('Codeine',$Call['_N'].'.Contractors');

        $Contract = F::Options($Call['Value']['_N']);

        if (!empty($Contract))
        {
            $Call['Value'] = F::Merge($Contract, $Call['Value']);

            foreach ($Contractors as $Contractor)
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
        }

        return $Call['Value'];
    });

    self::Fn('afterRun', function ($Call)
    {
        return $Call;
    });
