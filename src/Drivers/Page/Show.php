<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::setFn ('Do', function ($Call)
    {
        foreach ($Call['Places'] as $Place => $Template)
            $Call['Output'][$Place][] =
                array(
                    'Type'  => 'Template',
                    'Scope' => $Template['Scope'],
                    'ID' => $Template['ID'],
                    'Data' => $Call
                );

        return $Call;
    });