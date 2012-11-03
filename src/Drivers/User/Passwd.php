<?php

    /* Codeine
     * @author BreathLess
     * @description Activation email 
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Do', function ($Call)
    {
        return F::Run(null, $_SERVER['REQUEST_METHOD'], $Call);
    });

    self::setFn('GET', function ($Call)
    {

        return $Call;
    });

    self::setFn('POST', function ($Call)
    {
        // FIXME
        if ($Call['Session']['User']['Password'] == F::Run('Security.Hash', 'Get',
                            [
                                'Mode' => 'Secure',
                                'Value' => $Call['Request']['OldPassword']
                            ]))
        {


            F::Run('Entity', 'Update',
                array(
                     'Entity' => 'User',
                     'Where'  => $Call['Session']['User']['ID'],
                     'Purpose' => 'Reset',
                     'Data' =>
                        [
                            'Password' => $Call['Request']['NewPassword']
                        ])
                );

            $Call['Output']['Message'][] = array(
                    'Type' => 'Block',
                    'Class' => 'alert alert-success',
                    'Value' => '<l>User.Passwd:Success</l>'
                );
        }
        else
            $Call['Output']['Message'][] = array(
                    'Type' => 'Block',
                    'Class' => 'alert alert-danger',
                    'Value' => '<l>User.Passwd:Error.OldPasswd.Incorrect</l>'
                );


        return $Call;
    });