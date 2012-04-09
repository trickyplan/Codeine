<?php

    /* Codeine
     * @author BreathLess
     * @description Create Doctor
     * @package Codeine
     * @version 7.0
     */

    self::setFn('Do', function ($Call)
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $Call['Element'] = F::Run('Entity', 'Create',
                array (
                      'Entity' => 'User',
                      'Data' => $Call['Request']
                ));

            F::Run('Code.Flow.Hook','Run', $Call, array('On' => 'User.Created'));

            $Call['Headers']['Location:'] = '/user/' . $Call['Element']['ID'];
        }

        return $Call;
    });

