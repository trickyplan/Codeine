<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Before', function ($Call)
    {
        $Call['Where'] = F::Live($Call['Where']);

        $Call['Data'] = F::Run('Entity', 'Read', $Call, ['One' => true]);

        return $Call;
    });

    setFn('Do', function ($Call)
    {
        $Call['Output']['Content'] = $Call['Data'][$Call['Key']];
        return $Call;
    });