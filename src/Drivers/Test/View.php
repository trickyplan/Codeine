<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn ('Root', function ($Call)
    {
        $Call['Output']['Content'] =
            [
                [
                    'Type' => 'Heading',
                    'Level' => 1,
                    'Value' => 'Codeine '.$Call['Codeine']['Version'].' works!'
                ],
                [
                    'Type'  => 'Heading',
                    'Level' => 2,
                    'Value' => $_SERVER['HTTP_HOST']
                ]
            ];

        return $Call;
    });