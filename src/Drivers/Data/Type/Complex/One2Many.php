<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn ('Write', function ($Call)
    {
        F::Run('Entity', 'Delete', [
                                'Entity' => $Call['Entity'].'2'.$Call['Name'],
                                'Where' =>
                                    [
                                        $Call['Entity'] => isset($Call['Data']['ID'])? $Call['Data']['ID']: $Call['Data']['ID']
                                    ],
                                'One' => true
                           ]);

        if ((isset($Call['Purpose']) and $Call['Purpose'] != 'Delete') or !isset($Call['Purpose']))
            foreach ($Call['Value'] as $Value)
                F::Run('Entity', 'Create',
                    [
                        'Entity' => $Call['Entity'].'2'.$Call['Name'],
                        'One' => true,
                        'Data' =>
                            [
                                 $Call['Name'] => $Value,
                                 $Call['Entity'] => isset($Call['Data']['ID'])? $Call['Data']['ID']: $Call['Data']['ID']
                            ]
                ]);


        return null;
    });

    setFn('Read', function ($Call)
    {
        $Data = F::Run('Entity', 'Read', [
                                    'Entity' => $Call['Entity'].'2'.$Call['Name'],
                                    'Where' =>
                                        [
                                            $Call['Entity'] => $Call['Data']['ID']
                                        ]
                                    ]
                               );

        $Result = [];

        if(is_array($Data))
            foreach ($Data as $Row)
                $Result[] = $Row[$Call['Name']];

        return $Result;
    });