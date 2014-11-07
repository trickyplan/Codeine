<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Convert', function ($Call)
    {
        $Value = morpher_inflect($Call['Value'], $Call['Morpher']['Cases mapping'][$Call['Case']]);

        if ($Value{0} == '#')
            return $Call['Value'];
        else
            return $Value;
    });