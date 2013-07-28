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
                                'Scope' => 'Default',
                                'ID'    => 'UI/Form/WYSIWYG',
                                'Data' => $Call
                           ));
     });