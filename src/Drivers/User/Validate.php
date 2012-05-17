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

        if (empty($Element))
            $Call['Output'] = '';
        else
            $Call['Output'] = 'Занято';

        return $Call;
    });
