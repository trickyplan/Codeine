<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */


    self::setFn('Do', function ($Call)
    {
        return F::Run('Entity.List', 'Do', array('Entity' => 'Page', 'Template' => 'Control', 'PageURL' => '/control/Page/page', 'Context' => 'app'), $Call);
    });

    self::setFn('Create', function ($Call)
    {
        return F::Run('Entity.Create', 'Do', array('Entity' => 'Page', 'PageURL' => '/control/Page/page', 'Context' => 'app'), $Call);
     });

    self::setFn('Show', function ($Call)
    {
        return F::Run('Entity.Show.Static', 'Do', array('Entity' => 'Page', 'PageURL' => '/control/Page/page', 'Context' => 'app'), $Call);
     });

    self::setFn('Delete', function ($Call)
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        return F::Run('Entity.Delete', 'Do', $Call, ['Entity' => 'Page']);
    });

    self::setFn('Flush', function ($Call)
    {
        return F::Run('Entity.Delete', 'POST', $Call, ['Entity' => 'Page']);
    });

    self::setFn('Menu', function ($Call)
    {
        $Count = F::Run('Entity', 'Count', $Call, ['Entity' => 'Page']);
        return ['Count' => $Count];
    });