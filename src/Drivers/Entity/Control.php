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

        return F::Apply('Entity.List', 'Do', $Call, ['Entity' => $Call['Bundle'], 'Scope' => 'Control', 'Show Redirects' => true]);
    });

    setFn('Show', function ($Call)
    {
        $Call['Layouts'][] = [
            'Scope' => 'Entity',
            'ID' => 'Show.Static'
        ];

        return F::Apply('Entity.Show.Static', 'Do', $Call, ['Entity' => $Call['Bundle'], 'Scope' => 'Control']);
    });

    setFn('RAW', function ($Call)
    {
        $Call['Layouts'][] = [
            'Scope' => 'Entity',
            'ID' => 'Show.RAW'
        ];

        return F::Apply('Entity.Show.RAW', 'Do', $Call, ['Entity' => $Call['Bundle'], 'Scope' => 'Control']);
     });

    setFn('Create', function ($Call)
    {
        $Call['Layouts'][] = [
            'Scope' => 'Entity',
            'ID' => 'Create'
        ];

        return F::Apply('Entity.Create', 'Do', $Call, ['Entity' => $Call['Bundle'], 'Scope' => 'Control']);
    });

    setFn('Update', function ($Call)
    {
        $Call['Layouts'][] = [
            'Scope' => 'Entity',
            'ID' => 'Update'
        ];

        return F::Apply('Entity.Update', 'Do', $Call, ['Entity' => $Call['Bundle'], 'Scope' => 'Control']);
    });

    setFn('Delete', function ($Call)
    {
        $Call['Layouts'][] = ['Scope' => 'Entity', 'ID' => 'Delete'];
        return F::Apply('Entity.Delete', 'Do', $Call, ['Entity' => $Call['Bundle'], 'Scope' => 'Control']);
    });

    setFn('Menu', function ($Call)
    {
        $Count = F::Run('Entity', 'Count', $Call, ['Entity' => $Call['Bundle'], 'Scope' => 'Control', 'Where' => []]);
        return ['Count' => $Count];
    });

    setFn('Export', function ($Call)
    {
        set_time_limit(0);
        $Elements = F::Run('Entity', 'Read', $Call,
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
        return F::Apply('Entity.Check', 'Do', $Call, ['Entity' => $Call['Bundle']]);
    });

    setFn('Search', function ($Call)
    {
        return F::Apply('Entity.Search', 'Do', $Call, ['Entity' => $Call['Bundle'], 'Scope' => 'Control']);
    });

    setFn('Accept', function ($Call)
    {
        $Call['Layouts'][] = ['Scope' => 'Entity', 'ID' => 'Accept'];
        return F::Apply('Entity.Accept', 'Do', $Call, ['Entity' => $Call['Bundle']]);
    });

    setFn('Reject', function ($Call)
    {
        $Call['Layouts'][] = ['Scope' => 'Entity', 'ID' => 'Reject'];
        return F::Apply('Entity.Reject', 'Do', $Call, ['Entity' => $Call['Bundle']]);
    });

    setFn('Touch', function ($Call)
    {
        $Call['Layouts'][] = ['Scope' => 'Entity', 'ID' => 'Touch'];
        return F::Apply('Entity.Touch', 'Do', $Call, ['Entity' => $Call['Bundle']]);
    });

    setFn('Import', function ($Call)
    {
        $Call['Layouts'][] = ['Scope' => 'Entity', 'ID' => 'Import'];
        return F::Apply('Entity.Import', 'Do', $Call, ['Entity' => $Call['Bundle']]);
    });

    setFn('Populate', function ($Call)
    {
        return F::Apply('Entity.Populator', 'Do', $Call, ['Entity' => $Call['Bundle']]);
    });

    setFn('Renumerate', function ($Call)
    {
        return F::Apply('Entity.Renumerate', 'Do', $Call, ['Entity' => $Call['Bundle']]);
    });