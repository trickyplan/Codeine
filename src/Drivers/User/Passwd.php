<?php

    /* Codeine
     * @author BreathLess
     * @description Activation email 
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        return F::Run(null, $Call['HTTP']['Method'], $Call);
    });

    setFn('GET', function ($Call)
    {

        return $Call;
    });

    setFn('POST', function ($Call)
    {
        // FIXME
        if ($Call['Session']['User']['Password'] == F::Run('Security.Hash', 'Get',
        [
            'Mode' => 'Secure',
            'Value' => $Call['Request']['OldPassword']
        ]))
        {
            F::Run('Entity', 'Update',
                [
                     'Entity' => 'User',
                     'Where'  => $Call['Session']['User']['ID'],
                     'Purpose' => 'Reset',
                     'One' => true,
                     'Data' =>
                        [
                            'Password' => $Call['Request']['NewPassword']
                        ]
                ]);

            $Call['Output']['Message'][] =
                [
                    'Type' => 'Block',
                    'Class' => 'alert alert-success',
                    'Value' => '<l>User.Passwd:Success</l>'
                ];

            F::Log('User '.$Call['Session']['User']['ID'].' changed password ', LOG_INFO, 'Security');
        }
        else
            $Call['Output']['Message'][] =
                [
                    'Type' => 'Block',
                    'Class' => 'alert alert-danger',
                    'Value' => '<l>User.Passwd:Error.OldPasswd.Incorrect</l>'
                ];


        return $Call;
    });