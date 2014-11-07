<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Convert', function ($Call)
    {
        return morpher_inflect($Call['Value'], $Call['Morpher']['Cases mapping'][$Call['Case']]);
    });