<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Do', function ($Call)
    {
        return F::Run(null, $_SERVER['REQUEST_METHOD'], $Call);
    });

    self::setFn('GET', function ($Call)
    {
        foreach ($Call['Roles'] as $Role['ID'] => $Role)
            foreach ($Call['Rights'] as $Right['ID'] => $Right)
                $Table[$Role['ID']][$Right['ID']] = (isset($Role['Rights'][$Right['ID']]) && $Role['Rights'][$Right['ID']]);


        $Call['Output']['Content'][] =
            [
                'Name' => 'Rights',
                'Type' => 'CheckTable',
                'Columns' => array_keys($Call['Rights']),
                'Value' => $Table
            ];
        return $Call;
    });


    self::setFn('POST', function ($Call)
    {
        // TODO Realize "POST" function
        d(__FILE__, __LINE__, $Call['Request']);

        return $Call;
    });