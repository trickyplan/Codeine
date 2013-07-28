<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Make', function ($Call)
    {
        return F::Run ('View', 'Load', $Call,
                       array(
                            'Scope' => 'UI',
                            'ID'    => 'HTML/Form/Panel',
                            'Data'  => $Call
                       ));
    });