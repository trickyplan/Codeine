<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::Fn ('Widget.New', function ($Call)
        {

            return $Call;
        });

    self::Fn ('Widget.Enter', function ($Call)
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

    self::Fn ('Check', function ($Call)
        {
            return $Call['User']['Auth.Password'] == sha1($Call['Password']); // FIXME

        });