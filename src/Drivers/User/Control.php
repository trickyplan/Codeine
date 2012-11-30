<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        return F::Run('Entity.List', 'Do', array('Entity' => 'User', 'Template' => 'Control', 'PageURL' => '/control/User/page', 'Context' => 'app'), $Call);
    });

    setFn('Show', function ($Call)
    {
        return F::Run('Entity.Show.Static', 'Do', array('Entity' => 'User', 'PageURL' => '/control/User/page', 'Context' => 'app'), $Call);
     });

    setFn('Create', function ($Call)
    {
        return F::Run('Entity.Create', 'Do', $Call, ['Entity' => 'User', 'Scope' => 'Control', 'CAPTCHA' => ['Bypass' => true]]);
    });

    setFn('Update', function ($Call)
    {
        return F::Run('Entity.Update', 'Do', array('Entity' => 'User', 'Where' => $Call['ID']), $Call);
    });

    setFn('Login', function ($Call)
    {
        $Call = F::Run('Entity', 'Load', $Call, ['Entity' => 'User']);

        $Call['Session'] = F::Run('Security.Auth', 'Attach', ['User' => $Call['Where']]);

        $Call = F::Hook('afterLogin', $Call);

        return $Call;
    });

    setFn('Delete', function ($Call)
    {
        $Call['Layouts'][] = ['Scope' => 'Entity', 'ID' => 'Delete']; // FIXME
        return F::Run('Entity.Delete', 'Do', array('Entity' => 'User', 'Where' => $Call['ID']), $Call);
    });

    setFn('Flush', function ($Call)
    {
        return F::Run('Entity.Delete', 'Do', array('Entity' => 'User'), $Call);
    });

    setFn('Menu', function ($Call)
    {
        $Count = F::Run('Entity', 'Count', $Call, ['Entity' => 'User']);

        return ['Count' => $Count];
    });