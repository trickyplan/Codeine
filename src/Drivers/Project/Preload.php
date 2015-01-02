<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Project'] = F::Live(F::loadOptions('Project'));
        $Call['Version'] = F::loadOptions('Version');
        return $Call;
     });