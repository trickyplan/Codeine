<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Rights'] = F::loadOptions('Security.Access.Rights')['Access']['Rights'];
        return F::Run(null, $Call['HTTP']['Method'], $Call);
    });

    setFn('GET', function ($Call)
    {
        $User = F::Run('Entity', 'Read', $Call,
                    [
                         'Entity' => 'User',
                         'One' => true
                    ]);

        $Rights = (array) explode(',',$User['Rights']);

        $Call['Output']['Content']['Form'] =
                [
                    'Type' => 'Form'
                ];

        foreach ($Call['Rights'] as $RightName => $Right)
            $Call['Output']['Form'][] =
                [
                    'Type' => 'Form.Checkbox',
                    'Name' => 'Rights['.$RightName.']',
                    'TrueValue' => true,
                    'Label' => 'User.Rights:'.$RightName,
                    'Value' => in_array($RightName, $Rights)
                ];


        return $Call;
    });

    setFn('POST', function ($Call)
    {
        if (!isset($Call['Request']['Rights']) or null == $Call['Request']['Rights'])
            $NewRights = '';
        else
            $NewRights = implode(',', array_keys($Call['Request']['Rights']));

        F::Run('Entity', 'Update', [
                       'Entity' => 'User',
                       'Where' => $Call['Where'],
                       'One' => true,
                       'Data' =>
                           [
                               'Rights' => $NewRights
                           ]
               ]);

        $Call['Output']['Content'][] =
            [
                'Type' => 'Block',
                'Class' => 'alert alert-success',
                'Value' => 'OK'
            ];

        return $Call;
    });