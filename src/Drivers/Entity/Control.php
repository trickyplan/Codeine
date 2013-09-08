<?php

    /* Codeine
     * @author BreathLess
     * @description Unified Control 
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Layouts'][] = [
            'Scope' => 'Entity',
            'ID' => 'List',
            'Context' => ''
        ];

        return F::Run('Entity.List', 'Do', $Call, ['Entity' => $Call['Bundle'], 'Scope' => 'Control', 'Show Redirects' => true]);
    });

    setFn('Show', function ($Call)
    {
        $Call['Layouts'][] = [
            'Scope' => 'Entity',
            'ID' => 'Show.Static'
        ];

        return F::Run('Entity.Show.Static', 'Do', $Call, ['Entity' => $Call['Bundle'], 'Scope' => 'Control']);
    });

    setFn('RAW', function ($Call)
    {
        $Call['Layouts'][] = [
            'Scope' => 'Entity',
            'ID' => 'Show.RAW'
        ];

        return F::Run('Entity.Show.RAW', 'Do', $Call, ['Entity' => $Call['Bundle'], 'Scope' => 'Control']);
     });

    setFn('Create', function ($Call)
    {
        $Call['Layouts'][] = [
            'Scope' => 'Entity',
            'ID' => 'Create'
        ];

        return F::Run('Entity.Create', 'Do', $Call, ['Entity' => $Call['Bundle'], 'Scope' => 'Control']);
    });

    setFn('Update', function ($Call)
    {
        $Call['Layouts'][] = [
            'Scope' => 'Entity',
            'ID' => 'Update'
        ];

        return F::Run('Entity.Update', 'Do', $Call, ['Entity' => $Call['Bundle'], 'Scope' => 'Control']);
    });

    setFn('Delete', function ($Call)
    {
        $Call['Layouts'][] = ['Scope' => 'Entity', 'ID' => 'Delete'];
        return F::Run('Entity.Delete', 'Do', $Call, ['Entity' => $Call['Bundle'], 'Scope' => 'Control']);
    });

    setFn('Menu', function ($Call)
    {
        $Count = F::Run('Entity', 'Count', $Call, ['Entity' => $Call['Bundle'], 'Scope' => 'Control', 'Where' => []]);
        return ['Count' => $Count];
    });

    setFn('Export', function ($Call)
    {
        $Elements = F::Run('Entity', 'Read',
                    [
                         'Entity' => $Call['Bundle']
                    ]);

        $Call['Renderer'] =
            [
                'Service' =>  'View.JSON',
                'Method' =>  'Render'
            ];

        $Call['Output']['Content'] = $Elements;

        return $Call;
    });

    setFn('Check', function ($Call)
    {
        return F::Run('Entity.Check', 'Do', $Call, ['Entity' => $Call['Bundle']]);
    });

    setFn('Accept', function ($Call)
    {
        $Call['Layouts'][] = ['Scope' => 'Entity', 'ID' => 'Accept'];
        return F::Run('Entity.Accept', 'Do', $Call, ['Entity' => $Call['Bundle']]);
    });

    setFn('Reject', function ($Call)
    {
        $Call['Layouts'][] = ['Scope' => 'Entity', 'ID' => 'Reject'];
        return F::Run('Entity.Reject', 'Do', $Call, ['Entity' => $Call['Bundle']]);
    });

    setFn('Touch', function ($Call)
    {
        return F::Run('Entity.Touch', 'Do', $Call, ['Entity' => $Call['Bundle']]);
    });

    setFn('Populate', function ($Call)
    {
        return F::Run('Entity.Populator', 'Do', $Call, ['Entity' => $Call['Bundle']]);
    });

    setFn('Renumerate', function ($Call)
    {
        return F::Run('Entity.Renumerate', 'Do', $Call, ['Entity' => $Call['Bundle']]);
    });

    setFn('Dict', function ($Call)
    {
        $Call['Renderer'] = ['Service' => 'View.JSON', 'Method' => 'Render'];

        if (isset($Call['Request']['Query']))
            $Where = ['Title.ru' => '~/'.$Call['Request']['Query'].'/'];
        else
            $Where = $Call['Request']['Where'];

        $Call['Output']['Content'] =
            F::Run ('Entity.List', 'RAW', $Call,
                    [
                        'Entity' => $Call['Bundle'],
                        'Key' => 'Title.ru',
                        'Where' => $Where
                    ]);

        $Rows = [];
        foreach ($Call['Output']['Content'] as $Key => $Value)
            $Rows[] = ['id' => $Key, 'text' => $Value];

        $Call['Output']['Content'] = $Rows;

        return $Call;
    });