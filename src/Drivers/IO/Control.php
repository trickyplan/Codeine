<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
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
                    'Value' => 'Control/Short',
                    'Data' => F::Merge(array('Name' => $Name), $Storage)
                );

            $Info = F::Run('IO', 'Execute', array('Storage' => $Name, 'Execute' => 'Status'));

            foreach ($Info as &$Row)
                $Row[0] = '<l>'.$Storage['Driver'].'.'.$Row[0].'</l>';

            $Call['Output']['Storage_'.$Name][] =
                array(
                    'Type' => 'Table',
                    'Headless' => true,
                    'Value' => $Info
                );
        }

        return $Call;
    });