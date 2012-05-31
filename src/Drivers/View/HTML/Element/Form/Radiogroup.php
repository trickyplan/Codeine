<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Make', function ($Call)
    {
        $Output = '';

        foreach($Call['Options'] as $Value)
            $Output.= F::Run('View', 'LoadParsed',
                array('Scope' => 'Default',
                      'ID' => 'UI/HTML/Form/Radio',
                      'Data' => F::Merge($Call, array('Value' => $Value))));

        return F::Run('View', 'LoadParsed', array('Scope' => 'Default', 'ID' => 'UI/HTML/Form/Radiogroup', 'Data' => F::Merge($Call, array(
            'Radios' => $Output))));
     });