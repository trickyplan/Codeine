<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Make', function ($Call)
    {
        $Output = '';

        foreach($Call['Options'] as $IX => $Value)
            $Output.= F::Run('View', 'Load',
                array('Scope' => 'Default',
                      'ID' => 'UI/Form/Radio',
                      'Data' => F::Merge($Call, array('Value' => $Value, 'Checked' => ($Value == $Call['Value']? 'true': null)))));

        return F::Run('View', 'Load', array('Scope' => 'Default', 'ID' => 'UI/Form/Radiogroup', 'Data' => F::Merge($Call, array(
            'Radios' => $Output))));
     });