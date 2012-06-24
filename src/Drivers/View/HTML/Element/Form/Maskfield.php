<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn('Make', function ($Call)
    {
        return F::Run('View', 'LoadParsed', array('Scope' => 'Default', 'ID' => 'UI/HTML/Form/Maskfield', 'Data' => $Call));
     });