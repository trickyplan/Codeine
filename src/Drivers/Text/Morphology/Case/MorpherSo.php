<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Convert', function ($Call)
    {
        $Value = morpher_inflect($Call['Value'], $Call['Morpher']['Cases mapping'][$Call['Case']]);

        if (empty($Value) or $Value{0} == '#')
            return $Call['Value'];
        else
            return $Value;
    });