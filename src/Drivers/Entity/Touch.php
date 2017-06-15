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
    
            if (empty($Old))
                ;
            else
            {
                foreach ($Old as $IX => $Object)
                {
                    $New = F::Run('Entity', 'Update', $Call,
                        [
                            'Where' => $Object['ID'],
                            'One' => true
                        ]
                    );
    
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
                        'Type'  => 'Block',
                        'Class' => 'alert alert-success',
                        'Value' => count($Table).' fields modified'
                    ];
                    
                    if (isset($Object['ID']))
                        array_unshift($Table , ['ID', $Object['ID'], '']);
                    $Call['Output']['Content'][] =
                        [
                            'Type' => 'Table',
                            'Value' => $Table
                        ];
                }
                
            }

        $Call = F::Hook('afterTouch', $Call);

        return $Call;
    });

    setFn('All', function ($Call)
    {
        $Call   = F::Apply('Entity', 'Load', $Call);
        $Total  = F::Run('Entity', 'Count', $Call);
        $Amount = ceil($Total/$Call['All']['Limit']);
        set_time_limit(10*$Total);

        $Call = F::Apply('Code.Progress', 'Start', $Call);

        $Call['Progress']['Max'] = $Amount;

        for ($i = 0; $i < $Amount; $i++)
        {
            F::Run('Entity', 'Update',
                [
                    'Entity'    => $Call['Entity'],
                    'Where'     => $Call['Where'],
                    'Data'      => [],
                    'One'       => false,
                    'Limit'     =>
                    [
                        'From' => $i*$Call['All']['Limit'],
                        'To'   => ($i+1)*$Call['All']['Limit']
                    ]
                ]);

            $Call['Progress']['Now']++;
            $Call = F::Apply('Code.Progress', 'Log', $Call);
            F::Log('Touch Iteration № '.($i+1)/$Amount, LOG_NOTICE);
        }

         $Call = F::Apply('Code.Progress', 'Finish', $Call);
        $Call['Output']['Content'][] = $Total.' elements processed';
        return $Call;
    });

    setFn('Test', function ($Call)
    {
        F::Run(null, "All", $Call, ['Entity' => 'User', 'Where' => ['ID'=> ['$gt'=>3]],'Live Fields' => ['VKontakte.DOB']]);
    });
