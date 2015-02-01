<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Dots = F::Run('Entity', 'Read', $Call,
        [
            'Entity'    => 'Metric',
        ]);

        $Data = [];

        foreach ($Dots as $Dot)
            $Data[] = [$Dot['Created'], $Dot['Value']];

        return $Data;
    });