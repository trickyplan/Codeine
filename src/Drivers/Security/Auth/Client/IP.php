<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn ('Widget.New', function ($Call)
        {

            return $Call;
        });

    setFn ('Widget.Enter', function ($Call)
        {
            return array(
                           'Place'  => 'Login.Form',
                           'Type' => 'Element',
                           'Widget'   => 'Form.IPCheck',
                           'Value'  => $_SERVER['REMOTE_ADDR'] // FIXME
                       );
        });

    setFn ('Check', function ($Call)
        {
            return $Call['User']['Auth']['IP'] == sha1($_SERVER['REMOTE_ADDR']); //FIXME
        });