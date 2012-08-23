<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Make', function ($Call)
    {
        $Output = '';

        foreach($Call['Options'] as $IX => $Value)
            $Output.= F::Run('View', 'LoadParsed',
                array('Scope' => 'Default',
                      'ID' => 'UI/HTML/Form/Radio',
                      'Data' => F::Merge($Call, array('Value' => $Value, 'Checked' => ($Value == $Call['Value']? 'true': null)))));

        return F::Run('View', 'LoadParsed', array('Scope' => 'Default', 'ID' => 'UI/HTML/Form/Radiogroup', 'Data' => F::Merge($Call, array(
            'Radios' => $Output))));
     });