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
            ['Entity' => 'Page', 'Scope' => 'Control', 'Sort' => ['Created' => 'DESC']]);
    });

    setFn('Create', function ($Call)
    {
        return F::Run('Entity.Create', 'Do', array('Entity' => 'Page', 'Context' => 'app'), $Call);
     });

    setFn('Update', function ($Call)
    {
        return F::Run('Entity.Update', 'Do', array('Entity' => 'Page', 'Context' => 'app'), $Call);
    });

    setFn('Show', function ($Call)
    {
        return F::Run('Entity.Show.Static', 'Do', array('Entity' => 'Page', 'PageURL' => '/control/Page/page', 'Context' => 'app'), $Call);
     });

    setFn('Delete', function ($Call)
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        return F::Run('Entity.Delete', 'Do', $Call, ['Entity' => 'Page']);
    });

    setFn('Flush', function ($Call)
    {
        return F::Run('Entity.Delete', 'POST', $Call, ['Entity' => 'Page']);
    });

    setFn('Menu', function ($Call)
    {
        $Count = F::Run('Entity', 'Count', $Call, ['Entity' => 'Page']);
        return ['Count' => $Count];
    });