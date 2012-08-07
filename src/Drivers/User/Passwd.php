<?php

    /* Codeine
     * @author BreathLess
     * @description Activation email 
     * @package Codeine
     * @version 7.6.2
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
        if ($Call['Session']['User']['Password'] == sha1($Call['Request']['OldPassword']))
        {
            F::Run('Entity', 'Set',
                array(
                     'Entity' => 'User',
                     'Where'  => $Call['Session']['User']['ID'],
                     'Data' => array ('Password' => sha1($Call['Request']['NewPassword'])) // FIXME
                ));

            array_unshift($Call['Output']['Content'], array(
                    'Type' => 'Block',
                    'Class' => 'alert alert-success',
                    'Value' => '<l>User.Passwd.Success</l>'
                ));
        }
        else
            array_unshift($Call['Output']['Content'], array(
                    'Type' => 'Block',
                    'Class' => 'alert alert-danger',
                    'Value' => '<l>User.Passwd.Error.OldPasswd.Incorrect</l>'
                ));


        return $Call;
    });