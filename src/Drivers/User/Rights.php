<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Rights'] = F::loadOptions('Security.Access.Rights')['Access']['Rights'];
        return F::Run(null, $_SERVER['REQUEST_METHOD'], $Call);
    });

    setFn('GET', function ($Call)
    {
        $User = F::Run('Entity', 'Read', $Call,
                    [
                         'Entity' => 'User'
                    ])[0];

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
        F::Run('Entity', 'Update', [
                       'Entity' => 'User',
                       'Where' => $Call['Where'],
                       'Data' =>
                           [
                               'Rights' => implode(',', array_keys($Call['Request']['Rights']))
                           ]
               ]);

        $Call = F::Run('System.Interface.Web', 'Redirect', $Call, ['Location' => '/control/User']);
        return $Call;
    });