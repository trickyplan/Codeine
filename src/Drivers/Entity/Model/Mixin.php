<?php

/* Codeine
     * @author BreathLess
     * @description Mixins Support 
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Process', function ($Call)
    {
        if (isset($Call['Mixin']))
            foreach ($Call['Mixin'] as $Mixin)
                $Call = F::Merge($Call, F::loadOptions('Entity.' . $Mixin));

        return $Call;
     });