<?php

    /* Codeine
     * @author BreathLess
     * @description Create Doctor
     * @package Codeine
     * @version 7.0
     */

    self::setFn('Do', function ($Call)
    {
        $Element = F::Run('Entity', 'Read', array('Entity' => 'User', 'Where' => array($Call['Request']['Key'] => $Call['Request']['Value'])));

        $Call['Renderer'] = 'View.JSON';

        $Call['Output'] = empty($Element);

        return $Call;
    });
