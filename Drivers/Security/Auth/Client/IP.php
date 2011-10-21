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
                           'Type' => 'Element',
                           'Widget'   => 'Form.IPCheck',
                           'Value'  => $_SERVER['REMOTE_ADDR'] // FIXME
                       );
        });

    self::Fn ('Check', function ($Call)
        {
            return $Call['User']['Auth.IP'] == sha1($_SERVER['REMOTE_ADDR']); //FIXME
        });