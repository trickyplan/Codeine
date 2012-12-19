<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */


setFn('Do', function ($Call)
    {
        return F::Run('Entity.List', 'Do', $Call,
            [
                'Entity' => 'Article',
                'Scope' => 'Control',
                'Context' => 'app',
                'Sort' => ['Created' => 'DESC']]);
    });

    setFn('Show', function ($Call)
    {
        return F::Run('Entity.Show.Static', 'Do', $Call,
            [
                'Scope' => 'Control',
                'Entity' => 'Article',
                'Context' => 'app'
            ]
        );
     });

    setFn('Create', function ($Call)
    {
        return F::Run('Entity.Create', 'Do', $Call, ['Entity' => 'Article']);
    });

    setFn('Update', function ($Call)
    {
        return F::Run('Entity.Update', 'Do', $Call, ['Entity' => 'Article']);
    });

    setFn('Delete', function ($Call)
    {
        return F::Run('Entity.Delete', 'Do', $Call, ['Entity' => 'Article']);
    });

    setFn('Menu', function ($Call)
    {
        $Count = F::Run('Entity', 'Count', $Call, ['Entity' => 'Article']);

        return ['Count' => $Count];
    });

    setFn('Order', function ($Call)
    {
        return $Call;
    });