<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        // TODO Realize "Do" function


        return $Call;
    });

    setFn('Storages', function ($Call)
    {
        $IO = F::loadOptions('IO');

        foreach ($IO['Storages'] as $Name => $Storage)
        {
            $Storage['Status'] = F::Run('IO', 'Open', ['Storage' => $Name]) !== null;

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