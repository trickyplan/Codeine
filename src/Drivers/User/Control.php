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
        return F::Run('Entity.Create', 'Do', $Call, ['Entity' => 'User', 'Scope' => 'Control']);
    });

    setFn('Update', function ($Call)
    {
        return F::Run('Entity.Update', 'Do', array('Entity' => 'User', 'Where' => $Call['ID']), $Call);
    });

    setFn('Magic', function ($Call)
    {
        $Call['User'] = F::Run('Entity', 'Read', $Call, array(
                                        'Entity' => 'User'
                                   ));

        switch($Call['User'][0]['Status'])
        {
            case 0:
                $Status = 1;
            break;

            case 1:
                $Status = -1;
            break;

            case -1:
                $Status = 1;
            break;
        }

        F::Run('Entity', 'Update', $Call, array(
                                        'Entity' => 'User',
                                        'Data' => array(
                                            'Status' => $Status
                                        )
                                   ));
        // FIXME
        $Call = F::Merge($Call, F::loadOptions('User.Entity'));

        $Call = F::Hook('afterMagic', $Call);

        return $Call;
    });

    setFn('Login', function ($Call)
    {
        $Call['Session'] = F::Run('Security.Auth', 'Attach', ['User' => $Call['ID']]);

        $Call = F::Hook('afterLogin', $Call);

        return $Call;
    });

    setFn('Delete', function ($Call)
    {
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