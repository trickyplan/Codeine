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

        $Call = F::Hook('beforeTouch', $Call);

        $Old = F::Run('Entity', 'Read', $Call, ['One' => false]);

        foreach ($Old as $IX => $Object)
        {
            $New = F::Run('Entity', 'Update', $Call, ['Data' => $Object, 'Where' => $Object['ID'], 'One' => false]);

            if (isset($Object['ID']))
                $Table = [['ID', $Object['ID']]];
            else
                $Table = [];

            foreach ($Call['Nodes'] as $Name => $Node)
            {
                $NewValue = F::Dot($New, $Name);
                $OldValue = F::Dot($Old[$IX], $Name);

                if ($OldValue == $NewValue || $Name == 'ID')
                    ;
                else
                    $Table[] = ['<l>'.$Call['Entity'].'.Entity:'.$Name.'</l>', $OldValue, $NewValue];
            }

            $Call['Output']['Content'][] =
            [
                'Type' => 'Table',
                'Value' => $Table
            ];
        }

        $Call['Output']['Content'][] =
            [
                'Type'  => 'Block',
                'Class' => 'alert alert-success',
                'Value' => count($New).' object touched'
            ];

        $Call = F::Hook('afterTouch', $Call);

        return $Call;
    });

    setFn('All', function ($Call)
    {
        $Call   = F::Apply('Entity', 'Load', $Call);
        $Total  = F::Run('Entity', 'Count', $Call);
        $Amount = ceil($Total/$Call['All']['Limit']);

        $Call = F::Apply('Code.Progress', 'Start', $Call);

        $Call['Progress']['Max'] = $Amount;

        for ($i = 0; $i < $Amount; $i++)
        {
            F::Run('Entity', 'Update', $Call,
                        [
                        'One' => false,
                        'Limit' => ['From' => $i*$Call['All']['Limit'],
                                    'To'   => ($i+1)*$Call['All']['Limit']
                                    ]
                        ]);

            $Call['Progress']['Now']++;
            $Call = F::Apply('Code.Progress', 'Log', $Call);
            F::Log('Touch Iteration № '.$i, LOG_WARNING);
        }

         $Call = F::Apply('Code.Progress', 'Finish', $Call);
        return $Call;
    });

    setFn('Test', function ($Call)
    {
        F::Run(null, "All", $Call, ['Entity' => 'User', 'Where' => ['ID'=> ['$gt'=>3]],'Live Fields' => ['VKontakte.DOB']]);
    });
