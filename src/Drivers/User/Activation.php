<?php

    /* Codeine
     * @author BreathLess
     * @description Activation email 
     * @package Codeine
     * @version 7.x
     */

    setFn('Send', function ($Call)
    {
        if (isset($Call['Data']['ID']))
            {
                $Call['Data']['Code'] = F::Run('Security.UID', 'Get');

                F::Run('IO', 'Write',
                    [
                         'Storage' => 'Primary',
                         'Scope' => 'Activation',
                         'Data' =>
                         [
                             'ID' => (int) $Call['Data']['Code'],
                             'User' => $Call['Data']['ID']
                         ]
                    ]);

                $Message['Scope'] = '"'.$Call['Data']['Title'].'" <'.$Call['Data']['EMail'].'>';
                $Message['ID']    = $Call['Subject'];

                $Message['Data']  = F::Run('View', 'Load',
                                                     [
                                                          'Scope' => 'User/Activation',
                                                          'ID' => 'EMail',
                                                          'Data' =>
                                                              F::Merge($Call,[
                                                                   'URLActivation' => $Call['Host'].'/activate/user/'.$Call['Data']['Code']
                                                              ])
                                                     ]);

                $Message['Headers'] = array ('Content-type:' => ' text/html; charset="utf-8"');


                F::Run('IO', 'Write', $Call, $Message,
                    [
                        'Storage' => 'EMail'
                    ]);


                list(,$Call['Data']['Server']) = explode('@', $Call['Data']['EMail']);

                $Call['Output']['Content'] =
                [
                    [
                        'Type'  => 'Template',
                        'Scope' => 'User/Activation',
                        'ID' => 'Needed',
                        'Data'  => $Call['Data']
                    ]
                ];

                if (isset($Call['Second']))
                    $Call['Output']['Message'][] =
                        [
                            'Type'  => 'Block',
                            'Class' => 'alert alert-success',
                            'Value'  => 'Письмо выслано повторно'
                        ];
            }

        return $Call;
    });

    setFn('Check', function ($Call)
    {
        $Activation = F::Run('IO', 'Read',
             [
                  'Storage' => 'Primary',
                  'Scope' => 'Activation',
                  'Where' => (int) $Call['Code']
             ])[0];

        if ($Activation !== null)
        {
            F::Run('Entity', 'Update', $Call,
                [
                     'Entity' => 'User',
                     'Where' => $Activation['User'],
                     'Data' =>
                         [
                            'Status' => 1
                         ]
                ]);

            F::Run('IO', 'Write',
                [
                     'Storage' => 'Primary',
                     'Scope' => 'Activation',
                     'Where' => $Call['Code'],
                     'Data' => null
                ]);

            if (isset($Call['Activation']['AutoLogin']) && $Call['Activation']['AutoLogin'])
                $Call = F::Apply('Session', 'Write', $Call, ['Data' => ['User' => $Activation['User']]]);

            $Call = F::Hook('Activation.Success', $Call);
        }
        else
            $Call = F::Hook('Activation.Failed', $Call);

        return $Call;
    });