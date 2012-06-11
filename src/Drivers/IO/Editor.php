<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Do', function ($Call)
    {
        $Data = F::Run('IO', 'Read',
            array(
                'Storage' => 'Primary',
                'Scope' => 'Shop'
            ));

        foreach ($Data as $IX => $Element)
            foreach ($Element as $Key => $Value)
                $Rows[$IX][] = array($Key, $Value);

        foreach ($Rows as $Row)
            $Call['Output']['Content'][] =
                array(
                    'Type' => 'Table',
                    'Value' => $Row
                );


        return $Call;
    });