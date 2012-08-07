<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.6.2
     */

    self::setFn('Make', function ($Call)
    {
        return F::Run ('View', 'LoadParsed', $Call,
                       array(
                            'Scope' => 'UI',
                            'ID'    => 'HTML/Form/Panel',
                            'Data'  => $Call
                       ));
    });