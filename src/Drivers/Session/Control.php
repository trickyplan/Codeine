<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */


    setFn('Do', function ($Call)
    {
        return F::Run('Entity.List', 'Do', array('Entity' => 'Session', 'Template' => 'Control', 'PageURL' => '/control/Session/page', 'Context' => 'app'), $Call);
    });

    setFn('Show', function ($Call)
    {
        return F::Run('Entity.Show.Static', 'Do', array('Entity' => 'Session', 'PageURL' => '/control/Session/page', 'Context' => 'app'), $Call);
     });

    setFn('Delete', function ($Call)
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        return F::Run('Entity.Delete', 'Do', $Call, ['Entity' => 'Session']);
    });

    setFn('Flush', function ($Call)
    {
        $Call = F::Run('Entity', 'Delete', $Call, ['Entity' => 'Session', 'Where' => ['Expire' => ['<' => time()], ['ID' => ['<>' => $Call['SID']]]]]);
        return F::Hook('afterDeletePost', $Call);
    });

    setFn('Menu', function ($Call)
    {
        $Count = F::Run('Entity', 'Count', $Call, ['Entity' => 'Session']);
        return ['Count' => $Count];
    });