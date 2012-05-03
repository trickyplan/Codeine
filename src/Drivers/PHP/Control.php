<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Do', function ($Call)
    {
        $Call['Output']['Content'] = array (
            array (
                'Type'  => 'Block',
                'Class' => 'alert alert-info',
                'Value' => 'PHP: '.phpversion()
            ));

        $INI = ini_get_all();

        foreach ($INI as $Key => $Value)
        {
            $Data[] = array ($Key, $Value['local_value']);

            if (isset($Call['Requirements'][$Key]) && ($Call['Requirements'][$Key] != $Value['local_value']))
                $Call['Output']['Content'][] =
                    array (
                        'Type'  => 'Block',
                        'Class' => 'alert alert-danger',
                        'Value' => 'Recommended value for <strong>'.$Key.'</strong> is "'.$Call['Requirements'][$Key].'"'
                    );
        }
            $Call['Output']['Content'][] =
                array (
                    'Type'  => 'Table',
                    'Value' => $Data
                );

         return $Call;
     });