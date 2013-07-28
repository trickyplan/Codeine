<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Make', function ($Call)
    {
        return F::Run('View', 'Load', array('Scope' => 'Default', 'ID' => 'UI/Form/Maskfield', 'Data' => $Call));
     });