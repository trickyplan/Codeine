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

     //   $Call = F::Hook('afterTouch', $Call);

        return $Call;
    });

    setFn('All', function ($Call)
    {
        $Call   = F::Apply('Entity', 'Load', $Call);
        $Total  = F::Run('Entity', 'Count', $Call);
        $Amount = ceil($Total/$Call['All']['Limit']);

        for ($i = 0; $i < $Amount; $i++)
        {
            $Call = F::Hook('beforeTouch', $Call);
            $Results = F::Run('Entity', 'Update', $Call, 
                        [
                        'One' => false,
                        'Limit' => ['From' => $i*$Call['All']['Limit'],
                                    'To'   => ($i+1)*$Call['All']['Limit']
                                    ]
                        ]);

            $Call['Output']['Content'][] =
                [
                    'Type' => 'Block',
                    'Value' => count($Results).' touched'
                ];
            F::Log('Touch Iteration № '.$i, LOG_WARNING);
     //   $Call = F::Hook('afterTouch', $Call);
        }
        return $Call;
    });

    setFn('Test', function ($Call)
    {
        F::Run(null, "All", $Call, ['Entity' => 'User', 'Where' => ['ID'=> ['$gt'=>3]],'Live Fields' => ['VKontakte.DOB']]);
    });
