<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.6.2
     */

    self::setFn('Do', function ($Call)
    {
        // TODO Realize "Do" function

        $IO = F::loadOptions('IO');

        foreach ($IO['Storages'] as $Name => $Storage)
        {
            $Call['Output']['Content'][] =
                array(
                    'Type' => 'Template',
                    'Scope' => 'IO',
                    'ID' => 'Control/Short',
                    'Data' => F::Merge(array('Name' => $Name), $Storage)
                );
        }

        return $Call;
    });