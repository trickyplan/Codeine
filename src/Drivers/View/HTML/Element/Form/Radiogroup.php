<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn('Make', function ($Call)
    {
        $Output = '';

        foreach($Call['Options'] as $IX => $Value)
            $Output.= F::Run('View', 'LoadParsed',
                array('Scope' => 'Default',
                      'ID' => 'UI/HTML/Form/Radio',
                      'Data' => F::Merge($Call, array('IX' => $IX, 'Value' => $Value, 'Checked' => ($Value == $Call['Value']? 'true': 'false')))));

        return F::Run('View', 'LoadParsed', array('Scope' => 'Default', 'ID' => 'UI/HTML/Form/Radiogroup', 'Data' => F::Merge($Call, array(
            'Radios' => $Output))));
     });