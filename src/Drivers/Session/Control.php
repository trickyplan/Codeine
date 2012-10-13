<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */


    self::setFn('Do', function ($Call)
    {
        return F::Run('Entity.List', 'Do', array('Entity' => 'Session', 'Template' => 'Control', 'PageURL' => '/control/Session/page', 'Context' => 'app'), $Call);
    });

    self::setFn('Show', function ($Call)
    {
        return F::Run('Entity.Show.Static', 'Do', array('Entity' => 'Session', 'PageURL' => '/control/Session/page', 'Context' => 'app'), $Call);
     });

    self::setFn('Delete', function ($Call)
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        return F::Run('Entity.Delete', 'Do', $Call, ['Entity' => 'Session']);
    });

    self::setFn('Flush', function ($Call)
    {
        return F::Run('Entity.Delete', 'POST', $Call, ['Entity' => 'Session', 'Expire' => ['<' => time()]]);
    });

    self::setFn('Menu', function ($Call)
    {
        $Count = F::Run('Entity', 'Count', $Call, ['Entity' => 'Session']);
        return ['Count' => $Count];
    });