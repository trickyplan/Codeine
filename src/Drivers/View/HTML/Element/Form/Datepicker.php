<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Make', function ($Call)
    {
        if (!isset($Call['Format']))
            $Call['Format'] = 'd.m.Y';

        $Call['Value'] = date($Call['Format'], F::Live($Call['Value']));

        return F::Run('View', 'LoadParsed', array('Scope' => 'Default', 'ID' => 'UI/Form/Datepicker', 'Data' => $Call));
     });