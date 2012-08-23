<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.6.2
     */

    self::setFn('Do', function ($Call)
    {
        return F::Run('Entity.List', 'Do', array('Entity' => 'User', 'Template' => 'Control', 'PageURL' => '/control/User/page', 'Context' => 'app'), $Call);
    });

    self::setFn('Show', function ($Call)
    {
        return F::Run('Entity.Show.Static', 'Do', array('Entity' => 'User', 'PageURL' => '/control/User/page', 'Context' => 'app'), $Call);
     });

    self::setFn('Create', function ($Call)
    {
        return F::Run('Entity.Create', 'Do', array('Entity' => 'User'), $Call);
    });

    self::setFn('Update', function ($Call)
    {
        return F::Run('Entity.Update', 'Do', array('Entity' => 'User', 'Where' => $Call['ID']), $Call);
    });

    self::setFn('Magic', function ($Call)
    {
        $Call['User'] = F::Run('Entity', 'Read', array(
                                        'Entity' => 'User',
                                        'Where' => $Call['ID']
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

        F::Run('Entity', 'Update', array(
                                        'Entity' => 'User',
                                        'Where' => $Call['ID'],
                                        'Data' => array(
                                            'Status' => $Status
                                        )
                                   ));
        // FIXME
        $Call = F::Merge($Call, F::loadOptions('Entity.User'));

        $Call = F::Hook('afterMagic', $Call);

        return $Call;
    });

    self::setFn('Delete', function ($Call)
    {
        return F::Run('Entity.Delete', 'Do', array('Entity' => 'User', 'Where' => $Call['ID']), $Call);
    });

    self::setFn('Flush', function ($Call)
    {
        return F::Run('Entity.Delete', 'Do', array('Entity' => 'User'), $Call);
    });