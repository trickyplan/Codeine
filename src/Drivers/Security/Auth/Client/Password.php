<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.1
     */

    self::setFn ('Widget.New', function ($Call)
        {

            return $Call;
        });

    self::setFn ('Widget.Enter', function ($Call)
        {
            return array(
                           'Place'  => 'Login.Form',
                           'Type'   => 'Element',
                           'Widget' => 'Form.Password',
                           'ID'     => 'Password',
                           'Class'  => array('Textfield', 'Password'),
                           'Name'   => 'Password'
                       );
        });

    self::setFn ('Check', function ($Call)
        {
            return $Call['User']['Auth.Password'] == sha1($Call['Password']); // FIXME

        });