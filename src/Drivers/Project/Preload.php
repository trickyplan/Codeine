<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Do', function ($Call)
    {
        $Call['Project'] = function() {return F::loadOptions('Project');};
        $Call['Version'] = F::loadOptions('Version');

        return $Call;
     });