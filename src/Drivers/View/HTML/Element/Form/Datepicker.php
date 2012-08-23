<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Make', function ($Call)
    {
        $Call['Value'] = date($Call['Format'],$Call['Value']);

        return F::Run('View', 'LoadParsed', array('Scope' => 'Default', 'ID' => 'UI/HTML/Form/Datepicker', 'Data' => $Call));
     });