<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */


    setFn('Do', function ($Call)
    {
        return F::Run('Entity.List', 'Do', array('Entity' => 'Article', 'Template' => 'Control', 'ArticleURL' => '/control/Article/page', 'Context' => 'app'), $Call);
    });

    setFn('Create', function ($Call)
    {
        return F::Run('Entity.Create', 'Do', array('Entity' => 'Article', 'Context' => 'app'), $Call);
     });

    setFn('Update', function ($Call)
    {
        return F::Run('Entity.Update', 'Do', array('Entity' => 'Article', 'Context' => 'app'), $Call);
    });

    setFn('Show', function ($Call)
    {
        return F::Run('Entity.Show.Static', 'Do', array('Entity' => 'Article', 'ArticleURL' => '/control/Article/page', 'Context' => 'app'), $Call);
     });

    setFn('Delete', function ($Call)
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        return F::Run('Entity.Delete', 'Do', $Call, ['Entity' => 'Article']);
    });

    setFn('Flush', function ($Call)
    {
        return F::Run('Entity.Delete', 'POST', $Call, ['Entity' => 'Article']);
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